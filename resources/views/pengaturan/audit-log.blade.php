@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Pengguna')

@section('content')
<div class="space-y-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari aktivitas..." class="rounded-lg border-gray-300 text-sm">
        <select name="modul" class="rounded-lg border-gray-300 text-sm">
            <option value="">Semua Modul</option>
            @foreach (['auth', 'siswa', 'kelas', 'jurusan', 'absensi', 'pelanggaran', 'catatan_bk', 'user', 'pengaturan', 'perangkat_piket', 'backup'] as $m)
                <option value="{{ $m }}" @selected(request('modul') === $m)>{{ ucfirst(str_replace('_', ' ', $m)) }}</option>
            @endforeach
        </select>
        <button class="bg-gray-100 hover:bg-gray-200 text-sm px-4 rounded-lg">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr><th class="px-4 py-3">Waktu</th><th class="px-4 py-3">Pengguna</th><th class="px-4 py-3">Aktivitas</th><th class="px-4 py-3">Modul</th><th class="px-4 py-3">IP</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->user?->name ?? 'Sistem' }}</td>
                        <td class="px-4 py-3">{{ $log->aktivitas }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-gray-100">{{ $log->modul }}</span></td>
                        <td class="px-4 py-3 text-gray-400">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
</div>
@endsection
