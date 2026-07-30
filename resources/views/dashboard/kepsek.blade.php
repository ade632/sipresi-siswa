@extends('layouts.app')
@section('title', 'Dashboard Kepala Sekolah')
@section('page-title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Banner Header Sambutan dengan Background Hitam/Gelap & Teks Kontras -->
    <div class="rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" style="background-color: #0f172a !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-blue-300 text-xs font-semibold mb-2 border border-slate-700" style="background-color: #1e293b !important;">
                <span>✨ Portal Monitoring Eksekutif</span>
            </div>
            <h1 class="text-base sm:text-lg font-bold" style="color: #ffffff !important;">Selamat Datang, Dr. Asep Suparman, S.Pi, M.Pd</h1>
            <p class="text-xs mt-1" style="color: #cbd5e1 !important;">Berikut adalah ringkasan pemantauan kehadiran dan kedisiplinan siswa SMK Negeri 1 Rejang Lebong hari ini.</p>
        </div>
        <div class="px-4 py-2.5 rounded-xl border text-xs font-medium shrink-0" style="background-color: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important;">
            📅 {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- 6 KARTU STATISTIK KEHADIRAN SISWA -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <x-stat-card label="Hadir" value="{{ $rekap['hadir'] }}" icon="✅" color="green" />
        <x-stat-card label="Terlambat" value="{{ $rekap['terlambat'] }}" icon="⏰" color="yellow" />
        <x-stat-card label="Izin" value="{{ $rekap['izin'] }}" icon="📄" color="blue" />
        <x-stat-card label="Sakit" value="{{ $rekap['sakit'] }}" icon="🤒" color="blue" />
        <x-stat-card label="Dispensasi" value="{{ $rekap['dispensasi'] }}" icon="📝" color="gray" />
        <x-stat-card label="Alpa" value="{{ $rekap['alpa'] }}" icon="🚫" color="red" />
    </div>

    <!-- GRAFIK VISUAL PROPORSI KEHADIRAN HARIAN -->
    @php
        $totalHadirSemua = array_sum($rekap);
        $totalHadirAman = $totalHadirSemua > 0 ? $totalHadirSemua : 1;
        
        $persenHadir = ($rekap['hadir'] / $totalHadirAman) * 100;
        $persenTerlambat = ($rekap['terlambat'] / $totalHadirAman) * 100;
        $persenIzinSakit = (($rekap['izin'] + $rekap['sakit']) / $totalHadirAman) * 100;
        $persenAlpa = (($rekap['alpa'] + $rekap['dispensasi']) / $totalHadirAman) * 100;
    @endphp
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900">Grafik Proporsi Status Kehadiran Hari Ini</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Visualisasi perbandingan persentase kehadiran seluruh siswa secara real-time</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-700 rounded-full">
                Total Tercatat: {{ $totalHadirSemua }} Siswa
            </span>
        </div>

        <!-- Progress Bar Grafik Multi-Warna -->
        <div class="w-full bg-gray-100 rounded-full h-3.5 overflow-hidden flex mb-4 shadow-inner">
            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ $persenHadir }}%" title="Hadir: {{ $rekap['hadir'] }}"></div>
            <div class="bg-amber-400 h-full transition-all duration-500" style="width: {{ $persenTerlambat }}%" title="Terlambat: {{ $rekap['terlambat'] }}"></div>
            <div class="bg-blue-500 h-full transition-all duration-500" style="width: {{ $persenIzinSakit }}%" title="Izin/Sakit"></div>
            <div class="bg-rose-500 h-full transition-all duration-500" style="width: {{ $persenAlpa }}%" title="Alpa/Dispensasi"></div>
        </div>

        <!-- Legend Keterangan -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-gray-100 text-xs">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="font-medium text-gray-700">Hadir: <b>{{ $rekap['hadir'] }}</b> ({{ number_format($persenHadir, 1) }}%)</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-amber-400 shrink-0"></span>
                <span class="font-medium text-gray-700">Terlambat: <b>{{ $rekap['terlambat'] }}</b> ({{ number_format($persenTerlambat, 1) }}%)</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-blue-500 shrink-0"></span>
                <span class="font-medium text-gray-700">Izin/Sakit: <b>{{ $rekap['izin'] + $rekap['sakit'] }}</b> ({{ number_format($persenIzinSakit, 1) }}%)</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-rose-500 shrink-0"></span>
                <span class="font-medium text-gray-700">Alpa/Dispensasi: <b>{{ $rekap['alpa'] + $rekap['dispensasi'] }}</b> ({{ number_format($persenAlpa, 1) }}%)</span>
            </div>
        </div>
    </div>

    <!-- 2 KOLOM: RANKING KELAS & PELANGGARAN -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Ranking Kedisiplinan Kelas -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Peringkat Kelas</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Akumulasi rata-rata kehadiran dan total poin pelanggaran tingkat kelas</p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 font-semibold px-2.5 py-1 rounded-lg border border-blue-100">Top Kelas</span>
                </div>
                
                <div class="space-y-3">
                    @php $medali = ['🥇', '🥈', '🥉']; @endphp
                    @forelse ($ranking as $i => $r)
                        @php
                            // Mengambil data kelas dan wali kelas secara aman
                            $kelasData = $r->kelas ?? \App\Models\Kelas::with('waliKelas')->find($r->kelas_id ?? $r->id ?? null);
                            $namaKelas = $kelasData->nama ?? $r->nama ?? ('Kelas #' . ($r->kelas_id ?? ''));
                            
                            $namaWali = '-';
                            if ($kelasData) {
                                if (method_exists($kelasData, 'namaWaliKelas') && $kelasData->namaWaliKelas()) {
                                    $namaWali = $kelasData->namaWaliKelas();
                                } elseif (isset($kelasData->waliKelas->name)) {
                                    $namaWali = $kelasData->waliKelas->name;
                                } elseif (isset($kelasData->wali_kelas)) {
                                    $namaWali = $kelasData->wali_kelas;
                                }
                            }
                            
                            // Menyamakan pembacaan variabel persentase kehadiran dari berbagai variasi nama kolom query controller
                            $persenKehadiran = $r->avg_kehadiran ?? $r->rata_kehadiran ?? $r->persen_kehadiran ?? 0;
                            $totalPoin = $r->total_poin ?? $r->total_poin_kelas ?? 0;
                        @endphp
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 {{ $i < 3 ? 'bg-blue-50/40 border-blue-100/80' : 'bg-gray-50/50' }}">
                            <div class="flex items-center gap-3">
                                <span class="text-base font-bold text-gray-700 w-5 text-center">{{ $medali[$i] ?? ($i + 1) }}</span>
                                <div>
                                    <h4 class="font-bold text-xs text-gray-900">{{ $namaKelas }}</h4>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Wali Kelas: {{ $namaWali }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
                                    {{ number_format($persenKehadiran, 1) }}% Hadir
                                </span>
                                <span class="text-xs font-semibold text-gray-700 bg-white px-2.5 py-1 rounded-lg border border-gray-200 shadow-2xs">
                                    {{ $totalPoin }} Poin Total
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-xs">Data ranking belum tersedia untuk periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pelanggaran Terbanyak Bulan Ini -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Pelanggaran Terbanyak Bulan Ini</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Statistik jenis pelanggaran yang sering terjadi</p>
                    </div>
                    <span class="text-xs bg-rose-50 text-rose-700 font-semibold px-2.5 py-1 rounded-lg border border-rose-100">Evaluasi</span>
                </div>
                        
                <div class="space-y-3">
                    @forelse ($pelanggaranTerbanyak as $p)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl border border-gray-100 bg-gray-50/50 text-xs">
                            <span class="font-medium text-gray-800">{{ $p->jenisPelanggaran->nama ?? 'Jenis Pelanggaran' }}</span>
                            <span class="font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-lg">{{ $p->total }}x Kasus</span>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-xs">Belum ada data pelanggaran bulan ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- AKSES LAPORAN LENGKAP -->
    <div class="rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" style="background-color: #0f172a !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-blue-300 text-xs font-semibold mb-2 border border-slate-700" style="background-color: #1e293b !important;">
                <span>📊 Arsip Dokumen Sekolah</span>
            </div>
            <h2 class="text-sm font-bold" style="color: #ffffff !important;">Akses Pusat Laporan Lengkap</h2>
            <p class="text-xs mt-0.5" style="color: #cbd5e1 !important;">Laporan harian, mingguan, bulanan, semester, dan tahunan tersedia dalam format PDF dan Excel, dapat difilter per kelas/jurusan</p>
        </div>
        <a href="{{ route('laporan.index') }}" style="background-color: #2563eb; color: #ffffff;" class="hover:bg-blue-700 text-xs font-bold px-5 py-2.5 rounded-xl shadow-md transition shrink-0 inline-flex items-center gap-1.5 cursor-pointer">
            Buka Halaman Laporan &rarr;
        </a>
    </div>
</div>
@endsection