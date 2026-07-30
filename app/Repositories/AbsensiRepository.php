<?php

namespace App\Repositories;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\SkorKedisiplinan;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AbsensiRepository implements AbsensiRepositoryInterface
{
    public function rekapHarian(string $tanggal): array
    {
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();

        // Mengambil data dan menormalkan key status menjadi huruf kecil di PHP
        $rekap = Absensi::whereDate('tanggal', $tanggal)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtolower(trim($item->status)) => $item->total];
            });

        $totalTercatat = $rekap->sum();

        return [
            'total_siswa' => $totalSiswaAktif,
            'hadir' => (int) ($rekap['hadir'] ?? 0),
            'terlambat' => (int) ($rekap['terlambat'] ?? 0),
            'izin' => (int) ($rekap['izin'] ?? 0),
            'sakit' => (int) ($rekap['sakit'] ?? 0),
            'dispensasi' => (int) ($rekap['dispensasi'] ?? 0),
            // Siswa yang belum ada record absensi sama sekali dianggap alpa sementara[cite: 5].
            'alpa' => (int) ($rekap['alpa'] ?? 0) + max($totalSiswaAktif - $totalTercatat, 0),
        ];
    }

    public function rekapPerKelas(int $kelasId, string $dariTanggal, string $sampaiTanggal): Collection
    {
        return Absensi::whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId))
            ->whereBetween('tanggal', [$dariTanggal, $sampaiTanggal])
            ->with('siswa')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('siswa_id');
    }

    public function rankingKedisiplinanKelas(string $periode): Collection
    {
        return SkorKedisiplinan::where('periode', $periode)
            ->join('siswa', 'siswa.id', '=', 'skor_kedisiplinan.siswa_id')
            ->select('siswa.kelas_id', 
                DB::raw('AVG(persen_kehadiran) as avg_kehadiran'),
                DB::raw('AVG(persen_ketepatan) as avg_ketepatan'),
                DB::raw('SUM(total_poin_pelanggaran) as total_poin'))
            ->groupBy('siswa.kelas_id')
            ->orderByDesc('avg_kehadiran')
            ->orderBy('total_poin')
            ->take(3) // Dibatasi hingga 3 besar
            ->get();
    }
}