<?php

namespace App\Http\Controllers\Pelanggaran;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Services\PoinPelanggaranService;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function __construct(protected PoinPelanggaranService $poinService) {}

    public function index(Request $request)
    {
        $pelanggaran = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'dicatatOleh'])
            ->when($request->kelas_id, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->when($request->cari, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%{$v}%")))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('pelanggaran.index', [
            'pelanggaran' => $pelanggaran,
            'kelas' => Kelas::orderBy('nama')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('pelanggaran.create', [
            'jenisPelanggaran' => JenisPelanggaran::orderBy('nama')->get(),
            'kelas' => Kelas::orderBy('nama')->get(),
            'siswa' => $request->kelas_id
                ? Siswa::where('kelas_id', $request->kelas_id)->where('status', 'aktif')->orderBy('nama')->get()
                : collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'jenis_pelanggaran_id' => ['required', 'exists:jenis_pelanggaran,id'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'bukti_foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $siswa = Siswa::findOrFail($data['siswa_id']);
        $jenis = JenisPelanggaran::findOrFail($data['jenis_pelanggaran_id']);

        $buktiPath = null;
        if ($request->hasFile('bukti_foto')) {
            $buktiPath = $request->file('bukti_foto')->store('bukti-pelanggaran', 'public');
        }

        $pelanggaran = $this->poinService->catat(
            $siswa, $jenis, $request->user(), $data['deskripsi'] ?? null, $buktiPath
        );

        AuditLog::catat("Mencatat pelanggaran {$jenis->nama} untuk {$siswa->nama}", 'pelanggaran');

        return redirect()->route('pelanggaran.index')
            ->with('success', "Pelanggaran berhasil dicatat. Total poin {$siswa->nama} sekarang: {$this->poinService->totalPoin($siswa)}.");
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $siswa = $pelanggaran->siswa;
        $pelanggaran->delete();

        $this->poinService->totalPoin($siswa); // trigger no-op read; skor recalculated by scheduled job

        AuditLog::catat("Menghapus catatan pelanggaran siswa {$siswa->nama}", 'pelanggaran');

        return back()->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}
