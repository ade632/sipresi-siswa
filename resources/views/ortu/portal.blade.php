@extends('layouts.app')
@section('title', 'Portal Orang Tua')
@section('page-title', 'Portal Orang Tua')

@push('scripts-head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="space-y-6">
    @if ($anak->count() > 1)
        <div class="flex gap-2">
            @foreach ($anak as $a)
                <a href="{{ route('ortu.portal', ['siswa_id' => $a->id]) }}"
                   class="px-4 py-2 rounded-lg text-sm {{ $a->id === $siswa->id ? 'bg-primary-600 text-white' : 'bg-white border text-gray-600' }}">
                    {{ $a->nama }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Kartu kehadiran hari ini, format sesuai contoh spesifikasi --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <p class="text-sm text-gray-500 mb-1">Informasi Kehadiran Hari Ini</p>
        @if ($absensiHariIni)
            <p class="text-gray-800">
                Anak Anda <strong>{{ $siswa->nama }}</strong> telah melakukan absensi pada pukul
                <strong>{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H.i') }} WIB</strong>.
            </p>
            @php
                $statusMap = [
                    'hadir' => ['Hadir Tepat Waktu', 'text-green-600'],
                    'terlambat' => ['Terlambat', 'text-yellow-600'],
                    'izin' => ['Izin', 'text-blue-600'],
                    'sakit' => ['Sakit', 'text-blue-600'],
                    'dispensasi' => ['Dispensasi', 'text-gray-600'],
                    'alpa' => ['Tidak Hadir Tanpa Keterangan', 'text-red-600'],
                ];
                [$label, $color] = $statusMap[$absensiHariIni->status];
            @endphp
            <p class="font-semibold mt-1 {{ $color }}">Status: {{ $label }}</p>
            @if ($absensiHariIni->jam_pulang)
                <p class="text-sm text-gray-500 mt-2">Pulang pukul {{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H.i') }} WIB</p>
            @endif
        @else
            <p class="text-gray-400">Belum ada catatan kehadiran untuk hari ini.</p>
        @endif
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
        <x-stat-card label="Total Poin Pelanggaran" value="{{ $totalPoin }}" icon="⚠️" color="{{ $totalPoin >= 40 ? 'red' : 'primary' }}" />
        <x-stat-card label="Kehadiran Bulan Ini" value="{{ $grafikSkor->last()->persen_kehadiran ?? 0 }}%" icon="✅" color="green" />
        <x-stat-card label="Ketepatan Waktu" value="{{ $grafikSkor->last()->persen_ketepatan ?? 0 }}%" icon="⏰" color="yellow" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h2 class="font-semibold text-gray-700 mb-3">Grafik Perkembangan Kedisiplinan</h2>
        <canvas id="grafikOrtu" height="150"></canvas>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b"><h2 class="font-semibold text-gray-700">Riwayat Absensi (30 Hari Terakhir)</h2></div>
            <div class="divide-y max-h-80 overflow-y-auto">
                @foreach ($riwayatAbsensi as $a)
                    <div class="px-5 py-2.5 flex justify-between text-sm">
                        <span class="text-gray-600">{{ $a->tanggal->format('d M Y') }}</span>
                        <span class="font-medium {{ $a->status === 'alpa' ? 'text-red-600' : ($a->status === 'terlambat' ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="px-5 py-4 border-b"><h2 class="font-semibold text-gray-700">Riwayat Pelanggaran</h2></div>
            <div class="divide-y max-h-80 overflow-y-auto">
                @forelse ($riwayatPelanggaran as $p)
                    <div class="px-5 py-2.5 text-sm">
                        <p class="text-gray-800">{{ $p->jenisPelanggaran->nama }}</p>
                        <p class="text-gray-400 text-xs">{{ $p->tanggal->format('d M Y') }} · {{ $p->poin_saat_ini }} poin</p>
                    </div>
                @empty
                    <p class="px-5 py-6 text-center text-gray-400 text-sm">Tidak ada pelanggaran tercatat. 🎉</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b"><h2 class="font-semibold text-gray-700">Catatan dari Guru BK</h2></div>
        <div class="divide-y">
            @forelse ($catatanBk as $c)
                <div class="px-5 py-3 text-sm">
                    <p class="text-gray-800 font-medium">{{ ucfirst(str_replace('_', ' ', $c->jenis)) }}</p>
                    <p class="text-gray-500">{{ $c->catatan }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $c->tanggal->format('d M Y') }} · {{ $c->guruBk->name }}</p>
                </div>
            @empty
                <p class="px-5 py-6 text-center text-gray-400 text-sm">Belum ada catatan dari Guru BK.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    new Chart(document.getElementById('grafikOrtu'), {
        type: 'line',
        data: {
            labels: [@foreach ($grafikSkor as $s) '{{ \Carbon\Carbon::createFromFormat('Y-m', $s->periode)->translatedFormat('M Y') }}', @endforeach],
            datasets: [
                { label: 'Kehadiran (%)', data: [@foreach ($grafikSkor as $s) {{ $s->persen_kehadiran }}, @endforeach], borderColor: '#2563eb', tension: 0.3 },
                { label: 'Ketepatan (%)', data: [@foreach ($grafikSkor as $s) {{ $s->persen_ketepatan }}, @endforeach], borderColor: '#16a34a', tension: 0.3 },
            ],
        },
        options: { scales: { y: { beginAtZero: true, max: 100 } } },
    });
</script>
@endsection
