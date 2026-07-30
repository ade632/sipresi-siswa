<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\SkorKedisiplinan;
use Carbon\Carbon;

/**
 * Menghitung & menyimpan skor kedisiplinan sebagai tabel derived/cache.
 * Dipanggil oleh: (1) scheduled job harian untuk semua siswa aktif,
 * dan (2) langsung setelah pelanggaran baru dicatat (lihat PoinPelanggaranService)
 * supaya status tidak stale sampai job berikutnya jalan.
 */
class SkorKedisiplinanService
{
    public function hitungUntukSiswa(Siswa $siswa, string $periode): SkorKedisiplinan
    {
        [$tahun, $bulan] = explode('-', $periode);
        $awalBulan = Carbon::create((int) $tahun, (int) $bulan, 1)->startOfMonth();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $absensiBulanIni = Absensi::where('siswa_id', $siswa->id)
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->get();

        $totalHariEfektif = max($absensiBulanIni->count(), 1);
        $totalHadir = $absensiBulanIni->whereIn('status', ['hadir', 'terlambat'])->count();
        $totalTepatWaktu = $absensiBulanIni->where('status', 'hadir')->count();

        $persenKehadiran = round(($totalHadir / $totalHariEfektif) * 100, 2);
        $persenKetepatan = $totalHadir > 0 ? round(($totalTepatWaktu / $totalHadir) * 100, 2) : 100;

        $totalPoin = (int) $siswa->pelanggaran()
            ->whereBetween('tanggal', [$awalBulan->toDateString(), $akhirBulan->toDateString()])
            ->sum('poin_saat_ini');

        $status = $this->tentukanStatus($persenKehadiran, $persenKetepatan, $totalPoin);

        return SkorKedisiplinan::updateOrCreate(
            ['siswa_id' => $siswa->id, 'periode' => $periode],
            [
                'persen_kehadiran' => $persenKehadiran,
                'persen_ketepatan' => $persenKetepatan,
                'total_poin_pelanggaran' => $totalPoin,
                'status' => $status,
                'dihitung_pada' => now(),
            ]
        );
    }

    /** Dipanggil oleh scheduled job (lihat routes/console.php) untuk semua siswa aktif. */
    public function hitungSemuaSiswa(?string $periode = null): int
    {
        $periode ??= now()->format('Y-m');
        $count = 0;

        Siswa::where('status', 'aktif')->chunk(200, function ($siswaChunk) use ($periode, &$count) {
            foreach ($siswaChunk as $siswa) {
                $this->hitungUntukSiswa($siswa, $periode);
                $count++;
            }
        });

        return $count;
    }

    protected function tentukanStatus(float $kehadiran, float $ketepatan, int $poin): string
    {
        if ($poin >= 75) {
            return 'perlu_pembinaan';
        }

        if ($poin >= 40 || $kehadiran < 80) {
            return 'cukup';
        }

        if ($poin >= 15 || $ketepatan < 90) {
            return 'baik';
        }

        return 'sangat_baik';
    }
}
