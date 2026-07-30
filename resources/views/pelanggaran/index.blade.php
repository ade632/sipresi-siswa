@extends('layouts.app')
@section('title', 'Pelanggaran')
@section('page-title', 'Data Pelanggaran Siswa')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama siswa..." class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white">
            <select name="kelas_id" class="rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white">
                <option value="">Semua Kelas</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-900 hover:bg-black text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm cursor-pointer">Filter</button>
        </form>
        <a href="{{ route('pelanggaran.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm cursor-pointer flex items-center gap-1.5">
            + Catat Pelanggaran
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-800 uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-4 font-bold">Tanggal</th>
                        <th class="px-5 py-4 font-bold">Siswa</th>
                        <th class="px-5 py-4 font-bold">Jenis Pelanggaran</th>
                        <th class="px-5 py-4 font-bold">Poin</th>
                        <th class="px-5 py-4 font-bold">Dicatat Oleh</th>
                        <th class="px-5 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pelanggaran as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 font-semibold text-gray-800">{{ $p->tanggal->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-gray-900 block">{{ $p->siswa->nama }}</span>
                                <span class="text-gray-500 text-xs font-medium">{{ $p->siswa->kelas->nama }}</span>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-800">{{ $p->jenisPelanggaran->nama }}</td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase shadow-2xs bg-red-100 text-red-900 border border-red-300">{{ $p->poin_saat_ini }} poin</span>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-700">{{ $p->dicatatOleh->name }}</td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('pelanggaran.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 px-3 py-1 rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-600 font-semibold">Belum ada pelanggaran tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $pelanggaran->links() }}
    </div>
</div>
@endsection