<?php

namespace App\Services;

use App\Models\JenisPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\PelanggaranTercatat;

class PoinPelanggaranService
{
    public function __construct(protected SkorKedisiplinanService $skorService) {}

    public function catat(
        Siswa $siswa,
        JenisPelanggaran $jenis,
        User $pencatat,
        ?string $deskripsi = null,
        ?string $buktiFoto = null,
    ): Pelanggaran {
        // Snapshot poin saat kejadian -- lihat catatan desain di migration
        // pelanggaran: supaya histori tidak berubah jika bobot poin diedit nanti.
        $pelanggaran = Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'jenis_pelanggaran_id' => $jenis->id,
            'tanggal' => now()->toDateString(),
            'deskripsi' => $deskripsi,
            'poin_saat_ini' => $jenis->poin,
            'dicatat_oleh' => $pencatat->id,
            'bukti_foto' => $buktiFoto,
        ]);

        // Rekalkulasi skor kedisiplinan periode berjalan secara langsung
        // (selain job terjadwal harian) supaya status BK & dashboard
        // langsung mencerminkan poin terbaru.
        $this->skorService->hitungUntukSiswa($siswa, now()->format('Y-m'));

        try {
            $siswa->orangTua?->notify(new PelanggaranTercatat($pelanggaran));
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim notifikasi pelanggaran: ' . $e->getMessage());
        }

        return $pelanggaran;
    }

    public function totalPoin(Siswa $siswa): int
    {
        return (int) $siswa->pelanggaran()->sum('poin_saat_ini');
    }

    /**
     * Tentukan status pembinaan berdasarkan akumulasi poin.
     * Ambang batas ini juga dipakai SkorKedisiplinanService.
     */
    public function statusDariPoin(int $totalPoin): string
    {
        return match (true) {
            $totalPoin >= 75 => 'perlu_pembinaan',
            $totalPoin >= 40 => 'cukup',
            $totalPoin >= 15 => 'baik',
            default => 'sangat_baik',
        };
    }
}