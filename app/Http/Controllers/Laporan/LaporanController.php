<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\AbsensiExport;
use App\Exports\PelanggaranExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama')->get();

        // Kalkulasi Peringkat Individu Akumulatif (Top 10) Real dari Database
        $peringkatIndividu = \App\Models\Siswa::with('kelas')
            ->withCount([
                'absensi as total_hadir' => function ($query) {
                    $query->whereIn('status', ['hadir', 'terlambat']);
                },
                'absensi as total_absen_tercatat'
            ])
            ->withSum('pelanggaran as total_poin', 'poin_saat_ini')
            ->get()
            ->map(function ($siswa) {
                $persen = $siswa->total_absen_tercatat > 0 
                    ? ($siswa->total_hadir / $siswa->total_absen_tercatat) * 100 
                    : 0;
                $siswa->persen_kehadiran = round($persen, 1);
                $siswa->total_poin = $siswa->total_poin ?? 0;
                return $siswa;
            })
            ->sort(function ($a, $b) {
                if ($b->persen_kehadiran === $a->persen_kehadiran) {
                    return $a->total_poin <=> $b->total_poin;
                }
                return $b->persen_kehadiran <=> $a->persen_kehadiran;
            })
            ->take(10)
            ->values();

        // Kalkulasi Peringkat Kelas Akumulatif (Top 3) Real dari Database
        $peringkatKelas = Kelas::with(['siswa.absensi', 'siswa.pelanggaran'])
            ->get()
            ->map(function ($kls) {
                $totalSiswa = $kls->siswa->count();
                if ($totalSiswa === 0) {
                    $kls->rata_kehadiran = 0;
                    $kls->total_poin_kelas = 0;
                    return $kls;
                }

                $akumulasiPersen = 0;
                $totalPoinKelas = 0;

                foreach ($kls->siswa as $siswa) {
                    $hadir = $siswa->absensi->whereIn('status', ['hadir', 'terlambat'])->count();
                    $totalAbsen = $siswa->absensi->count();
                    $persenSiswa = $totalAbsen > 0 ? ($hadir / $totalAbsen) * 100 : 0;
                    
                    $akumulasiPersen += $persenSiswa;
                    $totalPoinKelas += $siswa->pelanggaran->sum('poin_saat_ini');
                }

                $kls->rata_kehadiran = round($akumulasiPersen / $totalSiswa, 1);
                $kls->total_poin_kelas = $totalPoinKelas;
                return $kls;
            })
            ->sort(function ($a, $b) {
                if ($b->rata_kehadiran === $a->rata_kehadiran) {
                    return $a->total_poin_kelas <=> $b->total_poin_kelas;
                }
                return $b->rata_kehadiran <=> $a->rata_kehadiran;
            })
            ->take(3)
            ->values();

        return view('laporan.index', compact('kelas', 'peringkatIndividu', 'peringkatKelas'));
    }

    protected function rentangTanggal(Request $request): array
    {
        return match ($request->periode) {
            'harian' => [now()->toDateString(), now()->toDateString()],
            'mingguan' => [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()],
            'bulanan' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'semester' => [now()->subMonths(6)->toDateString(), now()->toDateString()],
            'tahunan' => [now()->startOfYear()->toDateString(), now()->endOfYear()->toDateString()],
            default => [$request->dari ?? now()->toDateString(), $request->sampai ?? now()->toDateString()],
        };
    }

    public function absensiPdf(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $kelas = $request->kelas_id ? Kelas::with('waliKelas')->find($request->kelas_id) : null;

        $absensi = Absensi::whereBetween('tanggal', [$dari, $sampai])
            ->when($request->kelas_id, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->with('siswa.kelas')
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.absensi', [
            'absensi' => $absensi,
            'dari' => $dari,
            'sampai' => $sampai,
            'kelas' => $kelas,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("laporan-absensi-{$dari}-sd-{$sampai}.pdf");
    }

    public function absensiExcel(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        return Excel::download(
            new AbsensiExport($dari, $sampai, $request->kelas_id),
            "laporan-absensi-{$dari}-sd-{$sampai}.xlsx"
        );
    }

    public function pelanggaranPdf(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $kelas = $request->kelas_id ? Kelas::with('waliKelas')->find($request->kelas_id) : null;

        $pelanggaran = Pelanggaran::whereBetween('tanggal', [$dari, $sampai])
            ->when($request->kelas_id, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->with(['siswa.kelas', 'jenisPelanggaran'])
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.pelanggaran', [
            'pelanggaran' => $pelanggaran,
            'dari' => $dari,
            'sampai' => $sampai,
            'kelas' => $kelas,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("laporan-pelanggaran-{$dari}-sd-{$sampai}.pdf");
    }

    public function pelanggaranExcel(Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        return Excel::download(
            new PelanggaranExport($dari, $sampai, $request->kelas_id),
            "laporan-pelanggaran-{$dari}-sd-{$sampai}.xlsx"
        );
    }

    public function perSiswaPdf(\App\Models\Siswa $siswa, Request $request)
    {
        [$dari, $sampai] = $this->rentangTanggal($request);

        $siswa->load(['kelas.waliKelas', 'catatanBk']);

        $absensi = $siswa->absensi()->whereBetween('tanggal', [$dari, $sampai])->get();
        $pelanggaran = $siswa->pelanggaran()->with('jenisPelanggaran')->whereBetween('tanggal', [$dari, $sampai])->get();

        $pdf = Pdf::loadView('laporan.pdf.per-siswa', compact('siswa', 'absensi', 'pelanggaran', 'dari', 'sampai'));

        return $pdf->download("laporan-{$siswa->nis}-{$siswa->nama}.pdf");
    }
}