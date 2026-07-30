<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        return view('master.tahun-ajaran.index', ['tahunAjaran' => TahunAjaran::orderByDesc('tanggal_mulai')->paginate(15)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $tahunAjaran = TahunAjaran::create($data);
        AuditLog::catat("Menambah tahun ajaran: {$tahunAjaran->nama} {$tahunAjaran->semester}", 'tahun_ajaran');

        return back()->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /** Aktifkan 1 tahun ajaran, otomatis nonaktifkan yang lain. */
    public function aktifkan(TahunAjaran $tahunAjaran)
    {
        TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
        $tahunAjaran->update(['is_aktif' => true]);

        AuditLog::catat("Mengaktifkan tahun ajaran: {$tahunAjaran->nama}", 'tahun_ajaran');

        return back()->with('success', "Tahun ajaran {$tahunAjaran->nama} kini aktif.");
    }
}
