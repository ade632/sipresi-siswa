<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\KalenderAkademik;
use App\Models\Siswa;
use Illuminate\Console\Command;

class TandaiAlpaOtomatis extends Command
{
    protected $signature = 'absensi:tandai-alpa';

    protected $description = 'Menandai siswa yang sama sekali belum absen sampai jam tertentu sebagai Alpa';

    public function handle(): int
    {
        $hariIni = now()->toDateString();

        if (KalenderAkademik::isLibur($hariIni)) {
            $this->info('Hari ini libur (kalender akademik), tidak ada yang ditandai.');

            return self::SUCCESS;
        }

        $jamSetting = \App\Models\JamAbsensiSetting::hariIni();
        if (! $jamSetting || ! $jamSetting->is_aktif) {
            $this->info('Hari ini bukan hari sekolah (libur rutin, contoh: Sabtu/Minggu), tidak ada yang ditandai.');

            return self::SUCCESS;
        }

        // Sistem butuh 1 user sebagai "pencatat" karena kolom dicatat_oleh wajib diisi.
        // Idealnya pakai wali kelas siswa, fallback ke akun Admin pertama.
        $adminFallbackId = \App\Models\User::whereHas('role', fn ($q) => $q->where('kode', \App\Models\Role::ADMIN))
            ->value('id');

        $siswaBelumAbsen = Siswa::with('kelas')->where('status', 'aktif')
            ->whereDoesntHave('absensi', fn ($q) => $q->whereDate('tanggal', $hariIni))
            ->get();

        foreach ($siswaBelumAbsen as $siswa) {
            Absensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => $hariIni,
                'status' => 'alpa',
                'metode' => 'manual',
                'dicatat_oleh' => $siswa->kelas->wali_kelas_id ?? $adminFallbackId,
                'keterangan' => 'Ditandai otomatis oleh sistem (tidak ada aktivitas absensi hingga batas waktu).',
            ]);
        }

        $this->info("{$siswaBelumAbsen->count()} siswa ditandai Alpa otomatis.");

        return self::SUCCESS;
    }
}
