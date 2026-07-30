@extends('layouts.app')
@section('title', 'Catat Pelanggaran')
@section('page-title', 'Catat Pelanggaran Siswa')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8" x-data="{ kelasId: '{{ request('kelas_id') }}' }">
    <form method="POST" action="{{ route('pelanggaran.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Kelas</label>
            <select onchange="window.location = '{{ route('pelanggaran.create') }}?kelas_id=' + this.value" class="w-full rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white">
                <option value="">-- Pilih Kelas untuk memuat daftar siswa --</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Siswa</label>
            <select name="siswa_id" required class="w-full rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white">
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Jenis Pelanggaran</label>
            <select name="jenis_pelanggaran_id" required class="w-full rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white">
                <option value="">-- Pilih Jenis Pelanggaran --</option>
                @foreach ($jenisPelanggaran as $j)
                    <option value="{{ $j->id }}">{{ $j->nama }} — {{ $j->poin }} poin ({{ ucfirst($j->kategori) }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Kejadian</label>
            <textarea name="deskripsi" rows="3" class="w-full rounded-lg border border-gray-300 text-sm py-2 px-3 focus:border-primary-500 focus:ring-primary-500 text-gray-900 bg-white" placeholder="Jelaskan kronologi singkat kejadian..."></textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Bukti Foto (opsional)</label>
            <input type="file" name="bukti_foto" accept="image/*" class="w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border file:border-gray-300 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-800 hover:file:bg-gray-100 cursor-pointer border border-gray-300 rounded-lg p-1 bg-white">
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('pelanggaran.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Batal</a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm cursor-pointer">Simpan Pelanggaran</button>
        </div>
    </form>
</div>
@endsection