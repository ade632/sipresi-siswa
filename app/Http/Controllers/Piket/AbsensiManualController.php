<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\AbsensiService;
use Illuminate\Http\Request;

/**
 * Absensi manual dipakai ketika kartu hilang/rusak, RFID reader
 * bermasalah, atau kondisi khusus lain (izin/sakit/dispensasi diketahui
 * di awal hari tanpa siswa perlu datang ke pos absensi).
 */
class AbsensiManualController extends Controller
{
    public function __construct(protected AbsensiService $absensiService) {}

    public function index(Request $request)
    {
        return view('absensi.manual', [
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
            'status' => ['required', 'in:hadir,terlambat,izin,sakit,dispensasi,alpa'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $siswa = Siswa::findOrFail($data['siswa_id']);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store('lampiran-absensi', 'public');
        }

        $absensi = $this->absensiService->catatManual(
            $siswa,
            $data['status'],
            $request->user(),
            $data['keterangan'] ?? null,
            $lampiranPath,
        );

        AuditLog::catat("Input absensi manual: {$siswa->nama} - {$data['status']}", 'absensi');

        return back()->with('success', "Absensi manual untuk {$siswa->nama} berhasil disimpan ({$data['status']}).");
    }

    /** Input massal untuk 1 kelas sekaligus (misalnya kelas study tour / izin kolektif). */
    public function storeMassal(Request $request)
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'status' => ['required', 'in:hadir,izin,sakit,dispensasi'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $siswaKelas = Siswa::where('kelas_id', $data['kelas_id'])->where('status', 'aktif')->get();

        foreach ($siswaKelas as $siswa) {
            $this->absensiService->catatManual($siswa, $data['status'], $request->user(), $data['keterangan'] ?? null);
        }

        AuditLog::catat("Input absensi massal kelas untuk {$siswaKelas->count()} siswa - {$data['status']}", 'absensi');

        return back()->with('success', "Absensi massal berhasil disimpan untuk {$siswaKelas->count()} siswa.");
    }
}
