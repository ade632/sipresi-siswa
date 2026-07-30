@extends('layouts.app')
@section('title', 'Pengguna & Hak Akses')
@section('page-title', 'Pengguna & Hak Akses')

@section('content')
<div class="pt-2 space-y-6 pb-12">
    <!-- Banner Header Utama dengan Background Hitam/Gelap -->
    <div class="rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" style="background-color: #0f172a !important; color: #ffffff !important;">
        <div>
            <h1 class="text-base sm:text-lg font-bold" style="color: #ffffff !important;">Pengguna & Hak Akses</h1>
            <p class="text-xs mt-0.5" style="color: #cbd5e1 !important;">Kelola akun, status keaktifan, dan hak akses pengguna sistem</p>
        </div>
    </div>

    <!-- 1. FORM TAMBAH / EDIT PENGGUNA -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-900">
                    {{ !empty($selectedUser) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h2>
                <p class="text-[11px] text-gray-500 mt-0.5">
                    {{ !empty($selectedUser) ? 'Perbarui informasi akun pengguna' : 'Daftarkan akun baru ke dalam sistem' }}
                </p>
            </div>
            @if(!empty($selectedUser))
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-blue-600 hover:underline bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">Batal</a>
            @endif
        </div>

        <div class="p-6">
            <form method="POST" action="{{ !empty($selectedUser) ? route('admin.users.update', $selectedUser->id) : route('admin.users.store') }}">
                @csrf
                @if(!empty($selectedUser))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">Role / Hak Akses <span class="text-red-500">*</span></label>
                        <select name="role_id" required class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium">
                            <option value="" disabled {{ empty($selectedUser) ? 'selected' : '' }}>Pilih Hak Akses</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}" {{ (!empty($selectedUser) && $selectedUser->role_id == $r->id) ? 'selected' : '' }}>
                                    {{ $r->nama ?? $r->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $selectedUser->name ?? '') }}" placeholder="Contoh: Ahmad Fauzi" required class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $selectedUser->email ?? '') }}" placeholder="email@sekolah.sch.id" required class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-1.5">
                            Kata Sandi 
                            @if(!empty($selectedUser))
                                <span class="text-gray-400 font-normal text-[10px]">(Opsional)</span>
                            @else
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="password" name="password" placeholder="Min. 6 karakter" {{ !empty($selectedUser) ? '' : 'required' }} class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" style="background-color: #2563eb; color: #ffffff;" class="hover:bg-blue-700 text-xs font-semibold px-6 py-2.5 rounded-xl shadow-sm transition cursor-pointer">
                        {{ !empty($selectedUser) ? 'Simpan Perubahan' : '+ Tambah Pengguna Baru' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. TABEL DAFTAR PENGGUNA DENGAN JARAK & PAGINATION -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Daftar Pengguna</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Semua akun yang terdaftar dalam sistem</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-bold">Nama</th>
                        <th class="px-5 py-3.5 text-left font-bold">Email</th>
                        <th class="px-5 py-3.5 text-left font-bold">Role</th>
                        <th class="px-5 py-3.5 text-left font-bold">Status</th>
                        <th class="px-5 py-3.5 text-right font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users ?? [] as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-4 font-bold text-gray-900">{{ $u->name }}</td>
                            <td class="px-5 py-4 text-gray-600 font-medium">{{ $u->email }}</td>
                            <td class="px-5 py-4 text-gray-700 font-medium">
                                {{ is_object($u->role) ? ($u->role->nama ?? $u->role->name ?? '-') : $u->role }}
                            </td>
                            <td class="px-5 py-4">
                                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-full text-[10px] font-semibold border transition cursor-pointer {{ ($u->status_aktif ?? true) ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}" title="Klik untuk mengubah status">
                                        {{ ($u->status_aktif ?? true) ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-lg shadow-sm transition hover:bg-amber-100 inline-block">Ubah</a>
                                
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-semibold text-red-700 bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg shadow-sm transition hover:bg-red-100 cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 font-medium text-xs">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bagian Navigasi Halaman (Pagination) -->
        @if(method_exists($users, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection