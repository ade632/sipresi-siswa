<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index()
    {
        return view('pengaturan.jenis-pelanggaran', [
            'jenisPelanggaran' => JenisPelanggaran::orderByDesc('poin')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'in:ringan,sedang,berat'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        JenisPelanggaran::create($data);
        AuditLog::catat("Menambah jenis pelanggaran: {$data['nama']}", 'jenis_pelanggaran');

        return back()->with('success', 'Jenis pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'in:ringan,sedang,berat'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $jenisPelanggaran->update($data);
        AuditLog::catat("Mengubah jenis pelanggaran: {$jenisPelanggaran->nama}", 'jenis_pelanggaran');

        return back()->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran)
    {
        $jenisPelanggaran->delete();
        AuditLog::catat("Menghapus jenis pelanggaran: {$jenisPelanggaran->nama}", 'jenis_pelanggaran');

        return back()->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }
}
