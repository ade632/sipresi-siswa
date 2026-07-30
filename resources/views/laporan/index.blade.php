@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Pusat Laporan')

@section('content')
<div class="space-y-6">

    <!-- KARTU UTAMA: LAPORAN ABSENSI & PELANGGARAN -->
    <div class="grid lg:grid-cols-2 gap-6">
        
        <!-- Laporan Absensi -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-4">Laporan Absensi</h2>
            <form method="GET" class="space-y-3" onsubmit="return false;">
                <select name="periode" id="periodeAbsensi" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan" selected>Bulanan</option>
                    <option value="semester">Semester</option>
                    <option value="tahunan">Tahunan</option>
                </select>
                <select id="kelasAbsensi" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="unduh('absensi', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded-lg">Unduh PDF</button>
                    <button type="button" onclick="unduh('absensi', 'excel')" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-lg">Unduh Excel</button>
                </div>
            </form>
        </div>

        <!-- Laporan Pelanggaran -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-semibold text-gray-700 mb-4">Laporan Pelanggaran</h2>
            <form method="GET" onsubmit="return false;" class="space-y-3">
                <select name="periode" id="periodePelanggaran" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan" selected>Bulanan</option>
                    <option value="semester">Semester</option>
                    <option value="tahunan">Tahunan</option>
                </select>
                <select id="kelasPelanggaran" class="w-full rounded-lg border-gray-300 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="unduh('pelanggaran', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded-lg">Unduh PDF</button>
                    <button type="button" onclick="unduh('pelanggaran', 'excel')" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-lg">Unduh Excel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SECTION: LEADERBOARD PERINGKAT (1-10 INDIVIDU & 1-3 KELAS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2 items-start">
        
        <!-- Peringkat Individu Akumulatif -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-sm">Peringkat Individu  </h3>
                <p class="text-xs text-gray-500 mt-0.5">Gabungan skor kehadiran terbaik dan jumlah poin pelanggaran terendah</p>
            </div>
            <div class="divide-y divide-gray-100 p-2 max-h-[460px] overflow-y-auto">
                @forelse($peringkatIndividu as $index => $siswa)
                <div class="p-3 flex items-center justify-between hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-3.5">
                        <span class="w-7 h-7 rounded-lg {{ $index < 3 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }} font-bold text-xs flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $siswa->nama }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $siswa->kelas->nama ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">{{ $siswa->persen_kehadiran }}%</span>
                        <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">{{ $siswa->total_poin }} Poin</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-xs text-gray-400 py-6">Belum ada data peringkat.</p>
                @endforelse
            </div>
        </div>

        <!-- Peringkat Kelas Akumulatif  -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-sm">Peringkat Kelas </h3>
                <p class="text-xs text-gray-500 mt-0.5">Akumulasi rata-rata kehadiran dan total poin pelanggaran tingkat kelas</p>
            </div>
            <div class="divide-y divide-gray-100 p-2">
                @forelse($peringkatKelas as $index => $kls)
                <div class="p-3 flex items-center justify-between hover:bg-gray-50 rounded-lg transition">
                    <div class="flex items-center gap-3.5">
                        <span class="w-7 h-7 rounded-lg {{ $index < 3 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }} font-bold text-xs flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $kls->nama }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Wali Kelas: {{ method_exists($kls, 'namaWaliKelas') ? ($kls->namaWaliKelas() ?? '-') : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded">{{ $kls->rata_kehadiran }}% Hadir</span>
                        <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">{{ $kls->total_poin_kelas }} Poin Total</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-xs text-gray-400 py-6">Belum ada data peringkat kelas.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script>
    function unduh(jenis, format) {
        const periode = document.getElementById('periode' + (jenis === 'absensi' ? 'Absensi' : 'Pelanggaran')).value;
        const kelasId = document.getElementById('kelas' + (jenis === 'absensi' ? 'Absensi' : 'Pelanggaran')).value;
        let url = `/laporan/${jenis}/${format}?periode=${periode}`;
        if (kelasId) url += `&kelas_id=${kelasId}`;
        window.location = url;
    }
</script>
@endsection