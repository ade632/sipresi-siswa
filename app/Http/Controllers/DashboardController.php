<?php

namespace App\Http\Controllers;

use App\Models\CatatanBk;
use App\Models\PerangkatPiket;
use App\Models\Pelanggaran;
use App\Models\Role;
use App\Models\Siswa;
use App\Repositories\Contracts\AbsensiRepositoryInterface;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected AbsensiRepositoryInterface $absensiRepo,
        protected SiswaRepositoryInterface $siswaRepo,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role->kode) {
            Role::ADMIN => $this->admin(),
            Role::GURU_PIKET => $this->guruPiket($user),
            Role::GURU_BK => $this->guruBk(),
            Role::KEPSEK => $this->kepsek(),
            Role::ORTU => redirect()->route('ortu.portal'),
            default => abort(403),
        };
    }

    protected function admin()
    {
        $hariIni = now()->toDateString();
        $rekap = $this->absensiRepo->rekapHarian($hariIni); //[cite: 9]

        return view('dashboard.admin', [
            'rekap' => $rekap,
            'totalSiswa' => $rekap['total_siswa'] ?? Siswa::where('status', 'aktif')->count(), //[cite: 9]
            'totalHadir' => $rekap['hadir'] ?? 0,
            'totalTerlambat' => $rekap['terlambat'] ?? 0,
            'totalIzin' => $rekap['izin'] ?? 0,
            'totalSakit' => $rekap['sakit'] ?? 0,
            'totalDispensasi' => $rekap['dispensasi'] ?? 0,
            'totalAlpa' => $rekap['alpa'] ?? 0,
            'totalPerangkatAktif' => PerangkatPiket::where('is_aktif', true)->count(), //[cite: 9]
            'pelanggaranTerbaru' => Pelanggaran::with(['siswa', 'jenisPelanggaran'])
                ->latest('id') //[cite: 9]
                ->limit(5)
                ->get(),
        ]);
    }

    /**
     * API Response untuk Polling Real-time Alpine.js di Dashboard
     */
    public function apiPelanggaranTerbaru()
    {
        $pelanggaran = Pelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->latest('id') //[cite: 9]
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_siswa' => $item->siswa->nama_lengkap ?? $item->siswa->nama ?? 'Siswa',
                    'nama_pelanggaran' => $item->jenisPelanggaran->nama_pelanggaran 
                                         ?? $item->jenisPelanggaran->nama 
                                         ?? $item->jenis_pelanggaran 
                                         ?? '-',
                    'poin' => $item->jenisPelanggaran->poin ?? $item->poin ?? 0,
                    'tanggal' => \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->translatedFormat('d-m-Y'),
                ];
            });

        return response()->json($pelanggaran);
    }

    protected function guruPiket($guru)
    {
        $hariIni = now()->toDateString(); //[cite: 9]

        return view('dashboard.piket', [
            'rekap' => $this->absensiRepo->rekapHarian($hariIni), //[cite: 9]
            'jadwalHariIni' => $guru->isGuruPiket()
                ? \App\Models\JadwalPiket::where('guru_id', $guru->id)->get() //[cite: 9]
                : collect(),
        ]);
    }

    protected function guruBk()
    {
        return view('dashboard.bk', [
            'siswaBermasalah' => $this->siswaRepo->siswaBermasalah(40), //[cite: 9]
            'catatanTerbaru' => CatatanBk::with(['siswa', 'guruBk'])->latest('tanggal')->limit(10)->get(), //[cite: 9]
        ]);
    }

    protected function kepsek()
    {
        $hariIni = now()->toDateString(); //[cite: 9]
        $periode = now()->format('Y-m'); //[cite: 9]

        return view('dashboard.kepsek', [
            'rekap' => $this->absensiRepo->rekapHarian($hariIni), //[cite: 9]
            'ranking' => $this->absensiRepo->rankingKedisiplinanKelas($periode), //[cite: 9]
            'pelanggaranTerbanyak' => Pelanggaran::whereMonth('tanggal', now()->month) //[cite: 9]
                ->select('jenis_pelanggaran_id')
                ->selectRaw('count(*) as total')
                ->groupBy('jenis_pelanggaran_id')
                ->with('jenisPelanggaran')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ]);
    }
}