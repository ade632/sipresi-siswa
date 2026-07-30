<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        return view('master.jurusan.index', ['jurusan' => Jurusan::withCount('kelas')->orderBy('nama')->paginate(15)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:jurusan,kode'],
            'nama' => ['required', 'string', 'max:100'],
        ]);

        Jurusan::create($data);
        AuditLog::catat("Menambah jurusan: {$data['nama']}", 'jurusan');

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:jurusan,kode,'.$jurusan->id],
            'nama' => ['required', 'string', 'max:100'],
        ]);

        $jurusan->update($data);
        AuditLog::catat("Mengubah jurusan: {$jurusan->nama}", 'jurusan');

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        AuditLog::catat("Menghapus jurusan: {$jurusan->nama}", 'jurusan');

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
