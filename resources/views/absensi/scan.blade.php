@extends('layouts.app')
@section('title', 'Scan Absensi')
@section('page-title', 'Scan Absensi Siswa')

@push('scripts-head')
    @vite('resources/js/scan-qr.js')
@endpush

@section('content')
<div
    x-data="scanAbsensi()"
    x-init="init()"
    class="flex flex-col gap-5 w-full max-w-full overflow-hidden"
>
    <!-- HEADER CARD -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-6 flex items-center justify-between flex-wrap gap-4 shadow-sm">
        <div>
            <h2 class="m-0 text-lg sm:text-xl font-bold text-white leading-tight">Scan Absensi Siswa</h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-400">Otomatis Masuk (termasuk terlambat) & Pulang (minimal jarak 60 menit)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start w-full">
        {{-- Kolom kiri & tengah: kamera QR + input RFID (7 Kolom di Desktop, Full di HP) --}}
        <div class="col-span-1 lg:col-span-7 space-y-4 w-full min-w-0">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-slate-900 text-sm m-0">Kamera Scan QR</h2>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                        :class="{
                            'bg-green-50 text-green-700 border border-green-200': kameraStatus === 'aktif',
                            'bg-gray-100 text-gray-500 border border-gray-200': kameraStatus === 'memuat',
                            'bg-red-50 text-red-700 border border-red-200': ['error','tidak-ada-izin','tidak-ada-kamera'].includes(kameraStatus)
                        }"
                        x-text="{ aktif: 'Kamera Aktif', memuat: 'Menyiapkan...', error: 'Bermasalah', 'tidak-ada-izin': 'Izin Ditolak', 'tidak-ada-kamera': 'Tidak Ada Kamera' }[kameraStatus]"
                    ></span>
                </div>

                {{-- Kotak Kamera Responsif (Adaptif Layar HP) --}}
                <div class="relative w-full max-w-lg mx-auto aspect-square sm:h-[400px] rounded-2xl overflow-hidden bg-black flex items-center justify-center shadow-inner border-2 border-slate-800">
                    <div id="qr-reader" class="w-full h-full overflow-hidden absolute inset-0 [&_video]:object-contain [&_video]:w-full [&_video]:h-full bg-black"></div>

                   {{-- Overlay Hasil Scan --}}
                    <div 
                        x-show="hasilTerakhir" 
                        x-cloak 
                        class="absolute inset-0 z-30 flex flex-col items-center justify-center p-6 text-center backdrop-blur-md transition-all"
                        :class="hasilTerakhir?.sukses ? (hasilTerakhir?.status === 'hadir' ? 'bg-green-950/95 text-white' : (hasilTerakhir?.status === 'pulang' ? 'bg-blue-950/95 text-white' : 'bg-amber-950/95 text-white')) : 'bg-red-950/95 text-white'"
                    >
                        <template x-if="hasilTerakhir?.sukses">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <span class="text-5xl sm:text-6xl mb-1" x-text="hasilTerakhir?.status === 'hadir' ? '✅' : (hasilTerakhir?.status === 'pulang' ? '🏁' : '⚠️')"></span>
                                <p class="text-xs sm:text-sm font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow" :class="hasilTerakhir?.status === 'hadir' ? 'bg-green-600 text-white' : (hasilTerakhir?.status === 'pulang' ? 'bg-blue-600 text-white' : 'bg-amber-600 text-white')" x-text="hasilTerakhir?.status"></p>
                                <p class="text-lg sm:text-xl font-black text-white mt-1 leading-tight px-2" x-text="hasilTerakhir?.siswa?.nama"></p>
                                <p class="text-xs sm:text-sm text-gray-200 font-bold" x-text="hasilTerakhir?.siswa?.kelas + ' (' + hasilTerakhir?.jam + ')'"></p>
                            </div>
                        </template>
                        <template x-if="!hasilTerakhir?.sukses">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <span class="text-5xl sm:text-6xl mb-1 animate-bounce">🚨</span>
                                <p class="text-xs sm:text-sm font-black bg-red-600 text-white uppercase tracking-widest px-4 py-1.5 rounded-full shadow">GAGAL / BELUM WAKTUNYA</p>
                                <p class="text-sm text-red-100 font-bold leading-relaxed px-4 mt-1" x-text="hasilTerakhir?.pesan"></p>
                            </div>
                        </template>
                        <button @click="hasilTerakhir = null" class="mt-6 bg-white/25 hover:bg-white/35 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition cursor-pointer shadow">
                            🔄 Tutup / Scan Lagi
                        </button>
                    </div>

                                      {{-- Status Loading Kamera --}}
                    <div x-show="!hasilTerakhir && kameraStatus === 'memuat'" x-cloak class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 bg-gray-900/95 z-10">
                        <svg class="animate-spin w-9 h-9 text-white/70 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.4 0 0 5.4 0 12h4z"></path></svg>
                        <p class="text-white/90 text-sm font-semibold" x-text="kameraPesan"></p>
                    </div>
                </div>

                <p class="text-xs text-slate-500 mt-3 text-center">Arahkan kamera ke kartu QR siswa dengan jelas.</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2.5">
                    <h2 class="font-bold text-slate-900 text-sm m-0">Input RFID</h2>
                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 font-semibold">Mode Keyboard (HID)</span>
                </div>
                <input
                    type="text"
                    x-ref="rfidInput"
                    x-model="rfidBuffer"
                    @keydown.enter.prevent="prosesRfid()"
                    autofocus
                    placeholder="Tempelkan kartu RFID siswa di sini..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm text-slate-900 outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        {{-- Kolom kanan: feed realtime hari ini (5 Kolom di Desktop, Full di HP) --}}
        <div class="col-span-1 lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-[400px] lg:h-[520px] lg:sticky lg:top-6 w-full min-w-0">
            <div class="px-4 py-3.5 border-b border-slate-200 flex items-center justify-between shrink-0 bg-slate-50 rounded-t-2xl">
                <h2 class="font-bold text-slate-900 text-sm m-0">Riwayat Scan Hari Ini</h2>
                <span class="text-[11px] px-2 py-0.5 bg-white border border-slate-300 rounded-full text-blue-600 font-bold" x-text="feed.length + ' Siswa'"></span>
            </div>
            <div class="overflow-y-auto flex-1 p-2 divide-y divide-slate-100">
                <template x-for="item in feed" :key="item.nama + item.jam">
                    <div class="p-2.5 flex items-center justify-between text-xs sm:text-sm border-b border-slate-100">
                        <div class="min-w-0 pr-2">
                            <p class="font-bold text-slate-900 text-xs m-0 truncate" x-text="item.nama"></p>
                            <p class="text-slate-500 text-[11px] mt-0.5 m-0" x-text="item.kelas"></p>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="flex items-center gap-1 justify-end">
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-50 text-green-700 border border-green-200" x-text="item.jam"></span>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="feed.length === 0" class="text-center text-slate-500 text-xs py-12">
                    <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-2 text-slate-400 text-base">📋</div>
                    Belum ada scan hari ini.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection