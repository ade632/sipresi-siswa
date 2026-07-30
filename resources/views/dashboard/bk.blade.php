@extends('layouts.app')
@section('title', 'Dashboard BK')
@section('page-title', 'Dashboard Guru BK')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Banner Header Sambutan dengan Background Hitam/Gelap & Ucapan Selamat Datang -->
    <div class="rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" style="background-color: #0f172a !important; color: #ffffff !important;">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-blue-300 text-xs font-semibold mb-2 border border-slate-700" style="background-color: #1e293b !important;">
                <span>🛡️ Layanan Bimbingan & Konseling</span>
            </div>
            <h1 class="text-base sm:text-lg font-bold" style="color: #ffffff !important;">Selamat Datang di Portal Bimbingan & Konseling</h1>
            <p class="text-xs mt-1" style="color: #cbd5e1 !important;">Pantau kedisiplinan siswa, penanganan poin pelanggaran, serta catatan konseling secara real-time.</p>
        </div>
        <div class="px-4 py-2.5 rounded-xl border text-xs font-medium shrink-0" style="background-color: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important;">
            📅 {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- KOTAK DUA KOLOM: SISWA PERLU PERHATIAN & CATATAN TERBARU -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Siswa Perlu Perhatian -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Siswa Perlu Perhatian (Poin ≥ 40)</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Daftar siswa dengan akumulasi poin pelanggaran tinggi</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg border border-amber-200">Perhatian Khusus</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-[420px] overflow-y-auto">
                    @forelse ($siswaBermasalah as $siswa)
                        @php
                            $status = $siswa->total_poin >= 75 ? ['Perlu Pembinaan Khusus', 'bg-red-50 text-red-700 border-red-200']
                                : ($siswa->total_poin >= 40 ? ['Cukup', 'bg-yellow-50 text-yellow-700 border-yellow-200'] : ['Baik', 'bg-green-50 text-green-700 border-green-200']);
                        @endphp
                        <a href="{{ route('bk.detail', $siswa) }}" class="px-6 py-3.5 flex items-center justify-between text-xs hover:bg-gray-50/80 transition">
                            <div>
                                <p class="font-bold text-gray-900">{{ $siswa->nama }}</p>
                                <p class="text-gray-500 mt-0.5">{{ $siswa->kelas->nama }} · NIS {{ $siswa->nis }}</p>
                            </div>
                            <div class="text-right flex flex-col items-end gap-1">
                                <span class="font-bold text-gray-900 bg-gray-100 px-2 py-0.5 rounded">{{ $siswa->total_poin }} poin</span>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $status[1] }}">{{ $status[0] }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-xs font-medium">Tidak ada siswa dengan poin signifikan saat ini. 🎉</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Catatan BK Terbaru -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Catatan BK Terbaru</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Riwayat penanganan dan catatan harian guru BK</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-200">Log BK</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-[420px] overflow-y-auto">
                    @forelse ($catatanTerbaru as $c)
                        <div class="px-6 py-3.5 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-gray-900">{{ $c->siswa->nama }} <span class="text-gray-400 font-normal">· {{ ucfirst($c->jenis) }}</span></span>
                            </div>
                            <p class="text-gray-600 line-clamp-2 mb-1.5 font-medium">{{ $c->catatan }}</p>
                            <p class="text-gray-400 text-[11px]">{{ $c->tanggal->format('d M Y') }} oleh {{ $c->guruBk->name }}</p>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-400 text-xs font-medium">Belum ada catatan BK.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection