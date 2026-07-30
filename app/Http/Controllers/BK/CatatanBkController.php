<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CatatanBk;
use App\Models\Siswa;
use App\Notifications\CatatanBkDitambahkan;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use App\Services\SkorKedisiplinanService;
use Illuminate\Http\Request;

class CatatanBkController extends Controller
{
    public function __construct(
        protected SiswaRepositoryInterface $siswaRepo,
        protected SkorKedisiplinanService $skorService,
    ) {}

    /** Daftar siswa yang butuh perhatian BK, diurutkan dari poin tertinggi. */
    public function index()
    {
        return view('bk.index', ['siswaBermasalah' => $this->siswaRepo->siswaBermasalah(15)]);
    }

    /** Halaman detail 1 siswa: riwayat absensi, pelanggaran, catatan BK, grafik. */
    public function detail(Siswa $siswa)
    {
        $siswa->load(['kelas', 'pelanggaran.jenisPelanggaran', 'catatanBk.guruBk']);

        $skor6Bulan = collect(range(0, 5))->map(function ($i) use ($siswa) {
            $periode = now()->subMonths($i)->format('Y-m');

            return $siswa->skorKedisiplinan()->where('periode', $periode)->first()
                ?? $this->skorService->hitungUntukSiswa($siswa, $periode);
        })->reverse()->values();

        return view('bk.detail', [
            'siswa' => $siswa,
            'totalPoin' => $siswa->totalPoinPelanggaran(),
            'grafikSkor' => $skor6Bulan,
        ]);
    }

    public function storeCatatan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'jenis' => ['required', 'in:konseling,pembinaan,panggilan_ortu,tindak_lanjut'],
            'catatan' => ['required', 'string', 'max:1000'],
            'status_tindak_lanjut' => ['required', 'in:baru,proses,selesai'],
        ]);

        $catatan = CatatanBk::create([
            'siswa_id' => $siswa->id,
            'guru_bk_id' => $request->user()->id,
            'tanggal' => now()->toDateString(),
            ...$data,
        ]);

        $siswa->orangTua?->notify(new CatatanBkDitambahkan($catatan));

        AuditLog::catat("Menambah catatan BK ({$data['jenis']}) untuk {$siswa->nama}", 'catatan_bk');

        return back()->with('success', 'Catatan BK berhasil disimpan.');
    }

    public function updateStatusTindakLanjut(Request $request, CatatanBk $catatanBk)
    {
        $data = $request->validate(['status_tindak_lanjut' => ['required', 'in:baru,proses,selesai']]);

        $catatanBk->update($data);
        AuditLog::catat("Mengubah status tindak lanjut BK: {$catatanBk->siswa->nama}", 'catatan_bk');

        return back()->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }
}
