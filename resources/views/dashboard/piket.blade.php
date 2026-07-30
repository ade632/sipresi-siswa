@extends('layouts.app')
@section('title', 'Dashboard Piket')
@section('page-title', 'Dashboard Guru Piket')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <x-stat-card label="Hadir Hari Ini" value="{{ $rekap['hadir'] }}" icon="✅" color="green" />
        <x-stat-card label="Terlambat" value="{{ $rekap['terlambat'] }}" icon="⏰" color="yellow" />
        <x-stat-card label="Alpa" value="{{ $rekap['alpa'] }}" icon="🚫" color="red" />
        <x-stat-card label="Izin/Sakit/Dispensasi" value="{{ $rekap['izin'] + $rekap['sakit'] + $rekap['dispensasi'] }}" icon="📄" color="blue" />
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <a href="{{ route('piket.scan.index') }}" class="bg-primary-600 hover:bg-primary-700 text-white rounded-xl p-6 flex items-center gap-4 transition">
            <span class="text-3xl">📷</span>
            <div>
                <p class="font-semibold">Mulai Scan Absensi</p>
                <p class="text-sm text-primary-100">Scan QR / Tempel Kartu RFID Siswa</p>
            </div>
        </a>
        <a href="{{ route('piket.manual.index') }}" class="bg-white border rounded-xl p-6 flex items-center gap-4 hover:bg-gray-50 transition">
            <span class="text-3xl">✍️</span>
            <div>
                <p class="font-semibold text-gray-800">Input Absensi Manual</p>
                <p class="text-sm text-gray-500">Untuk kartu hilang/rusak, izin, sakit, dispensasi</p>
            </div>
        </a>
    </div>
</div>
@endsection
