<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $absensi = Absensi::whereDate('tanggal', $tanggal)
            ->with('siswa.kelas')
            ->when($request->kelas_id, fn ($q, $v) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('jam_masuk')
            ->paginate(30)
            ->withQueryString();

        return view('absensi.rekap-harian', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'kelas' => Kelas::orderBy('nama')->get(),
        ]);
    }

    public function keterlambatan(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();

        $data = Absensi::where('status', 'terlambat')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->with('siswa.kelas')
            ->orderByDesc('tanggal')
            ->paginate(30)
            ->withQueryString();

        return view('absensi.rekap-keterlambatan', ['absensi' => $data, 'dari' => $dari, 'sampai' => $sampai]);
    }

    /** Siswa yang belum tercatat hadir hari ini (belum scan sama sekali). */
    public function belumHadir(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $siswaBelumAbsen = \App\Models\Siswa::where('status', 'aktif')
            ->whereDoesntHave('absensi', fn ($q) => $q->whereDate('tanggal', $tanggal))
            ->when($request->kelas_id, fn ($q, $v) => $q->where('kelas_id', $v))
            ->with('kelas')
            ->orderBy('nama')
            ->paginate(30)
            ->withQueryString();

        return view('absensi.belum-hadir', [
            'siswa' => $siswaBelumAbsen,
            'tanggal' => $tanggal,
            'kelas' => Kelas::orderBy('nama')->get(),
        ]);
    }
}
