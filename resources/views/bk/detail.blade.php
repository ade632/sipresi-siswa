@extends('layouts.app')
@section('title', 'Detail Siswa - ' . $siswa->nama)
@section('page-title', 'Riwayat Siswa: ' . $siswa->nama)

@push('scripts-head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Kartu Ringkasan Profil & Poin --}}
    <div class="bg-Black border border-slate-200 rounded-2xl p-6 md:p-8 text-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-6 relative overflow-hidden">
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-xl font-bold text-Red shadow-md shadow-blue-500/20">
                {{ strtoupper(substr($siswa->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold tracking-tight text-slate-900">{{ $siswa->nama }}</h1>
                <div class="flex items-center gap-2 text-sm text-slate-500 mt-1 flex-wrap">
                    <span class="text-slate-700 font-medium">{{ $siswa->kelas->nama }}</span>
                    <span class="text-slate-300">•</span>
                    <span>NIS: <strong class="text-slate-700">{{ $siswa->nis }}</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>NISN: <strong class="text-slate-700">{{ $siswa->nisn }}</strong></span>
                </div>
            </div>
        </div>
        <div class="text-left md:text-right relative z-10 bg-slate-50 border border-slate-200 px-5 py-3 rounded-xl">
            <div class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $totalPoin }} <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">poin</span>
            </div>
            @php
                $status = $totalPoin >= 75 ? ['🔴 Perlu Pembinaan Khusus', 'bg-red-50 text-red-600 border-red-200']
                    : ($totalPoin >= 40 ? ['🟡 Cukup', 'bg-amber-50 text-amber-600 border-amber-200'] : ($totalPoin >= 15 ? ['🔵 Baik', 'bg-blue-50 text-blue-600 border-blue-200'] : ['🟢 Sangat Baik', 'bg-emerald-50 text-emerald-600 border-emerald-200']));
            @endphp
            <span class="inline-block mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $status[1] }}">
                {{ $status[0] }}
            </span>
        </div>
    </div>

    <!-- Grid Utama: Grafik & Form Tambah -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Grafik Perkembangan -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between">
            <div class="mb-4">
                <h2 class="font-bold text-slate-800 text-base">Grafik Perkembangan Kedisiplinan (6 Bulan)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi persentase kehadiran dan ketepatan</p>
            </div>
            <div class="relative w-full" style="height: 220px;">
                <canvas id="grafikSkor"></canvas>
            </div>
        </div>

        <!-- Form Tambah Catatan BK -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="mb-4">
                <h2 class="font-bold text-slate-800 text-base">Tambah Catatan BK</h2>
                <p class="text-xs text-slate-500 mt-0.5">Berikan bimbingan atau catatan pembinaan baru untuk siswa</p>
            </div>
            <form method="POST" action="{{ route('bk.catatan.store', $siswa) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Layanan / Tindakan</label>
                    <select name="jenis" required class="w-full rounded-xl border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 py-2.5 px-3">
                        <option value="konseling">Konseling</option>
                        <option value="pembinaan">Pembinaan</option>
                        <option value="panggilan_ortu">Panggilan Orang Tua</option>
                        <option value="tindak_lanjut">Tindak Lanjut</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Isi Catatan</label>
                    <textarea name="catatan" rows="3" required placeholder="Isi catatan..." class="w-full rounded-xl border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 p-3 resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Status Tindak Lanjut</label>
                    <select name="status_tindak_lanjut" required class="w-full rounded-xl border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 py-2.5 px-3">
                        <option value="baru">Baru</option>
                        <option value="proses">Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold py-3 rounded-xl shadow-lg shadow-blue-500/25 transition-all duration-200">
                    Simpan Catatan
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Bawah: Riwayat -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Riwayat Pelanggaran -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="font-bold text-slate-800 text-base">Riwayat Pelanggaran</h2>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse ($siswa->pelanggaran as $p)
                    <div class="px-6 py-4 text-sm flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $p->jenisPelanggaran->nama }}</p>
                            <p class="text-slate-500 text-xs mt-0.5">{{ $p->tanggal->format('d M Y') }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-red-50 text-red-600 border border-red-100 font-bold text-xs">
                            {{ $p->poin_saat_ini }} poin
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">
                        Tidak ada riwayat pelanggaran.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Catatan BK -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="font-bold text-slate-800 text-base">Riwayat Catatan BK</h2>
            </div>
            <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
                @forelse ($siswa->catatanBk as $c)
                    @php
                        $statusSt = strtolower($c->status_tindak_lanjut);
                        $badgeClass = $statusSt == 'selesai' 
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                            : ($statusSt == 'proses' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200');
                    @endphp
                    <div class="px-6 py-4 text-sm hover:bg-slate-50/80 transition-colors">
                        <div class="flex justify-between items-start gap-2 mb-1.5">
                            <p class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $c->jenis)) }}</p>
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold border uppercase tracking-wider {{ $badgeClass }}">
                                {{ ucfirst($c->status_tindak_lanjut) }}
                            </span>
                        </div>
                        <p class="text-slate-700 leading-relaxed text-xs md:text-sm mb-2">{{ $c->catatan }}</p>
                        <p class="text-slate-500 text-xs flex items-center gap-1.5">
                            <span>{{ $c->tanggal->format('d M Y') }}</span>
                            <span>•</span>
                            <span class="text-blue-600 font-medium">{{ $c->guruBk->name }}</span>
                        </p>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">
                        Belum ada catatan BK.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('grafikSkor'), {
        type: 'line',
        data: {
            labels: [@foreach ($grafikSkor as $s) '{{ \Carbon\Carbon::createFromFormat('Y-m', $s->periode)->translatedFormat('M Y') }}', @endforeach],
            datasets: [
                {
                    label: 'Kehadiran (%)',
                    data: [@foreach ($grafikSkor as $s) {{ $s->persen_kehadiran }}, @endforeach],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Ketepatan (%)',
                    data: [@foreach ($grafikSkor as $s) {{ $s->persen_ketepatan }}, @endforeach],
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            }
        },
    });
</script>
@endsection