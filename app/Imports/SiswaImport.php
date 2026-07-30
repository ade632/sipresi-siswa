<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\KartuAkses;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Import siswa massal dari file Excel/CSV.
 *
 * Setiap baris diproses sebagai satu transaksi terpisah: kalau 1 baris
 * gagal (misalnya NIS duplikat atau kelas tidak ditemukan), baris itu
 * dilewati dan dicatat sebagai gagal, TANPA menggagalkan baris-baris
 * lain yang valid. Ini penting untuk import ratusan siswa sekaligus --
 * Admin tidak perlu mengulang dari awal hanya karena 1-2 baris salah.
 *
 * Setiap siswa yang berhasil diimpor otomatis mendapat:
 * - Akun orang tua (password default = NISN)
 * - Kartu QR (token UUID baru)
 * - Kartu RFID (jika kolom rfid_uid diisi)
 */
class SiswaImport implements ToCollection, WithHeadingRow
{
    /** @var array<int, array{nis: string, nama: string}> */
    public array $berhasil = [];

    /** @var array<int, array{baris: int, nama: string, alasan: string}> */
    public array $gagal = [];

    public function __construct(protected int $diimporOleh) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            // +2 karena baris ke-1 adalah heading, dan index dimulai dari 0.
            $nomorBaris = $index + 2;

            // Lewati baris yang benar-benar kosong (biasanya baris sisa di akhir file).
            if (collect($row)->filter(fn ($v) => trim((string) $v) !== '')->isEmpty()) {
                continue;
            }

            try {
                $this->prosesBaris($row, $nomorBaris);
            } catch (\Throwable $e) {
                $this->gagal[] = [
                    'baris' => $nomorBaris,
                    'nama' => trim((string) ($row['nama'] ?? '-')),
                    'alasan' => $e->getMessage(),
                ];
            }
        }
    }

    protected function prosesBaris($row, int $nomorBaris): void
    {
        $nis = trim((string) ($row['nis'] ?? ''));
        $nisn = trim((string) ($row['nisn'] ?? ''));
        $nama = trim((string) ($row['nama'] ?? ''));
        $namaKelas = trim((string) ($row['kelas'] ?? ''));
        $jenisKelamin = strtoupper(trim((string) ($row['jenis_kelamin'] ?? '')));
        $namaOrtu = trim((string) ($row['nama_ortu'] ?? ''));
        $emailOrtu = trim((string) ($row['email_ortu'] ?? ''));

        if (! $nis || ! $nisn || ! $nama || ! $namaKelas || ! $namaOrtu || ! $emailOrtu) {
            throw new \RuntimeException('Kolom wajib (nis, nisn, nama, kelas, nama_ortu, email_ortu) ada yang kosong.');
        }

        if (! in_array($jenisKelamin, ['L', 'P'], true)) {
            throw new \RuntimeException("Kolom jenis_kelamin harus diisi L atau P, ditemukan: '{$jenisKelamin}'.");
        }

        if (Siswa::where('nis', $nis)->exists()) {
            throw new \RuntimeException("NIS {$nis} sudah terdaftar di sistem.");
        }

        if (Siswa::where('nisn', $nisn)->exists()) {
            throw new \RuntimeException("NISN {$nisn} sudah terdaftar di sistem.");
        }

        $kelas = Kelas::where('nama', $namaKelas)->first();
        if (! $kelas) {
            throw new \RuntimeException("Kelas '{$namaKelas}' tidak ditemukan. Pastikan nama kelas persis sama dengan di menu Data Kelas.");
        }

        if (User::where('email', $emailOrtu)->exists()) {
            throw new \RuntimeException("Email orang tua {$emailOrtu} sudah dipakai akun lain.");
        }

        $rfidUid = trim((string) ($row['rfid_uid'] ?? ''));
        if ($rfidUid && KartuAkses::where('kode', $rfidUid)->exists()) {
            throw new \RuntimeException("UID RFID {$rfidUid} sudah dipakai kartu lain.");
        }

        $tanggalLahir = null;
        if (! empty($row['tanggal_lahir'])) {
            try {
                $tanggalLahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
            } catch (\Throwable) {
                $tanggalLahir = null; // format tanggal tidak dikenali, biarkan kosong daripada gagalkan baris
            }
        }

        DB::transaction(function () use ($row, $nis, $nisn, $nama, $kelas, $jenisKelamin, $namaOrtu, $emailOrtu, $rfidUid, $tanggalLahir) {
            $roleOrtu = Role::where('kode', Role::ORTU)->firstOrFail();

            $orangTua = User::create([
                'role_id' => $roleOrtu->id,
                'name' => $namaOrtu,
                'email' => $emailOrtu,
                'no_hp' => trim((string) ($row['no_hp_ortu'] ?? '')) ?: null,
                'password' => Hash::make($nisn),
            ]);

            $siswa = Siswa::create([
                'nis' => $nis,
                'nisn' => $nisn,
                'nama' => $nama,
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => $tanggalLahir,
                'alamat' => trim((string) ($row['alamat'] ?? '')) ?: null,
                'orang_tua_id' => $orangTua->id,
                'status' => 'aktif',
            ]);

            KartuAkses::create([
                'siswa_id' => $siswa->id,
                'tipe' => 'qr',
                'kode' => (string) Str::uuid(),
                'diterbitkan_oleh' => $this->diimporOleh,
            ]);

            if ($rfidUid) {
                KartuAkses::create([
                    'siswa_id' => $siswa->id,
                    'tipe' => 'rfid',
                    'kode' => $rfidUid,
                    'diterbitkan_oleh' => $this->diimporOleh,
                ]);
            }

            $this->berhasil[] = ['nis' => $nis, 'nama' => $nama];
        });
    }
}
