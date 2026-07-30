@extends('layouts.app')
@section('title', 'Backup Database')
@section('page-title', 'Backup & Restore Database')

@section('content')
<div class="space-y-4">
    <form method="POST" action="{{ route('admin.backup.store') }}">
        @csrf
        <button class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg">
            💾 Buat Backup Sekarang
        </button>
    </form>

    <p class="text-sm text-gray-500">
        Backup otomatis juga berjalan setiap malam pukul 01:00 (lihat <code>routes/console.php</code>).
        File backup lama (&gt; 30 hari) yang dibuat otomatis akan dihapus otomatis agar storage tidak penuh.
    </p>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr><th class="px-4 py-3">Nama File</th><th class="px-4 py-3">Ukuran</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3 text-right">Aksi</th></tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($files as $f)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $f['nama'] }}</td>
                        <td class="px-4 py-3">{{ $f['ukuran'] }}</td>
                        <td class="px-4 py-3">{{ $f['tanggal'] }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.backup.unduh', $f['nama']) }}" class="text-primary-600 hover:underline text-xs">Unduh</a>
                            <form action="{{ route('admin.backup.hapus', $f['nama']) }}" method="POST" class="inline" onsubmit="return confirm('Hapus file backup ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada file backup.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
