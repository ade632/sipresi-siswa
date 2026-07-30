@extends('layouts.app')
@section('title', 'Pilih Anak')
@section('page-title', 'Pilih Anak')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <p class="text-sm text-gray-500 mb-4">Anda terdaftar sebagai wali untuk lebih dari satu siswa. Pilih salah satu untuk melihat detailnya:</p>
    <div class="space-y-2">
        @foreach ($anak as $a)
            <a href="{{ route('ortu.portal', ['siswa_id' => $a->id]) }}" class="block px-4 py-3 rounded-lg border hover:bg-gray-50">
                <p class="font-medium text-gray-800">{{ $a->nama }}</p>
                <p class="text-sm text-gray-500">{{ $a->kelas->nama }} · NIS {{ $a->nis }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
