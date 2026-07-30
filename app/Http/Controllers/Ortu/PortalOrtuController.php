<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Services\SkorKedisiplinanService;
use Illuminate\Http\Request;

class PortalOrtuController extends Controller
{
    public function __construct(protected SkorKedisiplinanService $skorService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $anak = $user->anak()->with('kelas')->get();

        // Untuk kasus umum 1 ortu = 1 anak, langsung tampilkan detail.
        // Jika lebih dari 1 anak, tampilkan pemilih anak terlebih dahulu.
        $siswaTerpilih = $anak->count() === 1
            ? $anak->first()
            : $anak->firstWhere('id', $request->siswa_id);

        if (! $siswaTerpilih && $anak->count() > 1) {
            return view('ortu.pilih-anak', ['anak' => $anak]);
        }

        abort_if(! $siswaTerpilih, 404, 'Data anak tidak ditemukan. Hubungi Administrator sekolah.');

        $siswa = $siswaTerpilih;
        $absensiHariIni = $siswa->absensiHariIni;

        $riwayatAbsensi = $siswa->absensi()->latest('tanggal')->limit(30)->get();
        $riwayatPelanggaran = $siswa->pelanggaran()->with('jenisPelanggaran')->latest('tanggal')->limit(20)->get();
        $catatanBk = $siswa->catatanBk()->with('guruBk')->latest('tanggal')->limit(10)->get();

        $skor6Bulan = collect(range(0, 5))->map(function ($i) use ($siswa) {
            $periode = now()->subMonths($i)->format('Y-m');

            return $siswa->skorKedisiplinan()->where('periode', $periode)->first()
                ?? $this->skorService->hitungUntukSiswa($siswa, $periode);
        })->reverse()->values();

        return view('ortu.portal', [
            'anak' => $anak,
            'siswa' => $siswa,
            'absensiHariIni' => $absensiHariIni,
            'riwayatAbsensi' => $riwayatAbsensi,
            'riwayatPelanggaran' => $riwayatPelanggaran,
            'catatanBk' => $catatanBk,
            'totalPoin' => $siswa->totalPoinPelanggaran(),
            'grafikSkor' => $skor6Bulan,
        ]);
    }

    public function notifikasi(Request $request)
    {
        $notif = $request->user()->notifikasi()->latest()->paginate(20);

        return view('ortu.notifikasi', ['notifikasi' => $notif]);
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->user_id === auth()->id(), 403);

        $notifikasi->update(['dibaca_pada' => now()]);

        return back();
    }
}
