<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Role;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        return view('master.kelas.index', [
            'kelas' => Kelas::with(['jurusan', 'tahunAjaran', 'waliKelas'])->withCount('siswa')->orderBy('nama')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('master.kelas.create', [
            'jurusan' => Jurusan::orderBy('nama')->get(),
            'tahunAjaran' => TahunAjaran::orderByDesc('id')->get(),
            'guru' => User::whereHas('role', fn ($q) => $q->whereIn('kode', [Role::GURU_PIKET, Role::GURU_BK]))->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'integer', 'in:10,11,12'],
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'wali_kelas_nama' => ['nullable', 'string', 'max:150'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
        ]);

        $kelas = Kelas::create($data);

        AuditLog::catat("Menambah kelas: {$kelas->nama}", 'kelas');

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        return view('master.kelas.edit', [
            'kelas' => $kela,
            'jurusan' => Jurusan::orderBy('nama')->get(),
            'tahunAjaran' => TahunAjaran::orderByDesc('id')->get(),
            'guru' => User::whereHas('role', fn ($q) => $q->whereIn('kode', [Role::GURU_PIKET, Role::GURU_BK]))->get(),
        ]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'integer', 'in:10,11,12'],
            'jurusan_id' => ['required', 'exists:jurusan,id'],
            'wali_kelas_nama' => ['nullable', 'string', 'max:150'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
        ]);

        $kela->update($data);

        AuditLog::catat("Mengubah kelas: {$kela->nama}", 'kelas');

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        AuditLog::catat("Menghapus kelas: {$kela->nama}", 'kelas');

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
