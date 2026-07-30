<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('pengaturan.users', [
            'selectedUser' => null,
            'users' => User::with('role')->orderBy('name')->paginate(20),
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'nip_nik' => ['nullable', 'string', 'max:30'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'catat')) {
            AuditLog::catat("Menambah pengguna: {$user->name}", 'user');
        }

        return back()->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        return view('pengaturan.users', [
            'selectedUser' => $user,
            'users' => User::with('role')->orderBy('name')->paginate(20),
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'nip_nik' => ['nullable', 'string', 'max:30'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'catat')) {
            AuditLog::catat("Memperbarui data pengguna: {$user->name}", 'user');
        }

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $nama = $user->name;
        $user->delete();

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'catat')) {
            AuditLog::catat("Menghapus pengguna: {$nama}", 'user');
        }

        return back()->with('success', "Pengguna {$nama} berhasil dihapus.");
    }

    public function toggleAktif(User $user)
    {
        $column = \Schema::hasColumn('users', 'status_aktif') ? 'status_aktif' : 'is_active';
        $user->update([$column => ! $user->{$column}]);

        $status = $user->{$column} ? 'diaktifkan' : 'dinonaktifkan';
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'catat')) {
            AuditLog::catat("Akun {$user->name} {$status}", 'user');
        }

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:6']]);

        $user->update(['password' => Hash::make($data['password'])]);
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'catat')) {
            AuditLog::catat("Reset password untuk: {$user->name}", 'user');
        }

        return back()->with('success', "Kata sandi {$user->name} berhasil direset.");
    }
}