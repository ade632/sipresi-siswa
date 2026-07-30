@php
    $user = auth()->user();
    $linkClass = 'flex items-center gap-3 px-3.5 py-3 rounded-lg text-[14.5px] font-medium transition';
    $activeClass = 'bg-white/10 text-white shadow-inner';
    $inactiveClass = 'text-primary-100/70 hover:bg-white/5 hover:text-white';

    // Kumpulan path ikon SVG outline 24x24, satu gaya konsisten (menggantikan emoji demi tampilan lebih profesional).
    $iconPaths = [
        'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 3h7v9H3V3zm0 12h7v6H3v-6zm11-9h7v6h-7V6zm0 9h7v9h-7v-9z"/>',
        'student' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A9 9 0 0121 13.5V17M6 10.5V16c0 1 3 3 6 3s6-2 6-3v-5.5"/>',
        'class' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 10h16M9 4v14"/>',
        'compass' => '<circle cx="12" cy="12" r="9" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 9l-2 6-6 2 2-6 6-2z"/>',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 3v3m8-3v3M4 9h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/>',
        'calendar-days' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 3v3m8-3v3M4 9h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1zM8 13h.01M12 13h.01M16 13h.01M8 17h.01M12 17h.01"/>',
        'device' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 4h10a1 1 0 011 1v14a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1zM11 18h2"/>',
        'pin' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 21s7-6.5 7-11.5A7 7 0 105 9.5C5 14.5 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.3" stroke-width="1.6"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 9v3.5m0 3.5h.01M10.29 4.86L2.82 18a1.5 1.5 0 001.3 2.24h15.76a1.5 1.5 0 001.3-2.24L13.71 4.86a1.5 1.5 0 00-2.42 0z"/>',
        'user' => '<circle cx="12" cy="8" r="3.2" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/>',
        'log' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m-9-9h.01M6 4h12a1 1 0 011 1v14a1 1 0 01-1 1H6a1 1 0 01-1-1V5a1 1 0 011-1z"/>',
        'backup' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 7c0-1.1 3.6-2 8-2s8 .9 8 2-3.6 2-8 2-8-.9-8-2zm0 0v10c0 1.1 3.6 2 8 2s8-.9 8-2V7M4 12c0 1.1 3.6 2 8 2s8-.9 8-2"/>',
        'camera' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 8a2 2 0 012-2h1.2l.9-1.5A1 1 0 019 4h6a1 1 0 01.9.5L16.8 6H18a2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"/><circle cx="12" cy="13" r="3.2" stroke-width="1.6"/>',
        'pencil' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 012.8 2.8L12 17l-4 1 1-4 8.4-8.4z"/>',
        'list-check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 6h11M9 12h11M9 18h11M4 6l1 1 2-2M4 12l1 1 2-2M4 18l1 1 2-2"/>',
        'alert-circle' => '<circle cx="12" cy="12" r="9" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8v4m0 4h.01"/>',
        'clock' => '<circle cx="12" cy="12" r="9" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 7v5l3.5 2"/>',
        'ban' => '<circle cx="12" cy="12" r="9" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5.6 5.6l12.8 12.8"/>',
        'heart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 20s-7-4.35-9.5-8.5C.8 8 2.6 4.5 6 4.5c2 0 3.3 1.1 4 2.2.7-1.1 2-2.2 4-2.2 3.4 0 5.2 3.5 3.5 7C19 15.65 12 20 12 20z"/>',
        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 20V10m6 10V4m6 16v-7"/>',
        'family' => '<circle cx="8" cy="8" r="2.6" stroke-width="1.6"/><circle cx="16" cy="8" r="2.6" stroke-width="1.6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 19c0-2.5 2.2-4.5 5-4.5s5 2 5 4.5m-.5-4.4c.7-1.9 2.5-3.1 4.5-3.1 2.8 0 5 2 5 4.5"/>',
        'bell' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M6 8a6 6 0 0112 0c0 4 1.5 5.5 1.5 5.5H4.5S6 12 6 8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9.5 17a2.5 2.5 0 005 0"/>',
    ];

    // Closure lokal (bukan fungsi global) supaya aman dipakai berulang tanpa risiko "cannot redeclare".
    $ic = fn (string $name) => '<svg xmlns="http://www.w3.org/2000/svg" class="w-[21px] h-[21px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">'.($iconPaths[$name] ?? '').'</svg>';
@endphp

<a href="{{ route('dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
    {!! $ic('dashboard') !!} Dashboard
</a>

@if ($user->isAdmin())
    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Data Master</p>
    <a href="{{ route('admin.siswa.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.siswa.*') ? $activeClass : $inactiveClass }}">{!! $ic('student') !!} Data Siswa</a>
    <a href="{{ route('admin.kelas.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.kelas.*') ? $activeClass : $inactiveClass }}">{!! $ic('class') !!} Data Kelas</a>
    <a href="{{ route('admin.jurusan.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.jurusan.*') ? $activeClass : $inactiveClass }}">{!! $ic('compass') !!} Jurusan</a>
    <a href="{{ route('admin.tahun-ajaran.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.tahun-ajaran.*') ? $activeClass : $inactiveClass }}">{!! $ic('calendar') !!} Tahun Ajaran</a>
    <a href="{{ route('admin.kalender-akademik.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.kalender-akademik.*') ? $activeClass : $inactiveClass }}">{!! $ic('calendar-days') !!} Kalender Akademik</a>

    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Absensi & Perangkat</p>
    <a href="{{ route('admin.perangkat-piket.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.perangkat-piket.*') ? $activeClass : $inactiveClass }}">{!! $ic('device') !!} Perangkat Piket</a>
    <a href="{{ route('admin.pengaturan.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.pengaturan.*') ? $activeClass : $inactiveClass }}">{!! $ic('pin') !!} Pengaturan Sekolah</a>
    <a href="{{ route('admin.jenis-pelanggaran.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.jenis-pelanggaran.*') ? $activeClass : $inactiveClass }}">{!! $ic('warning') !!} Jenis Pelanggaran</a>

    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Sistem</p>
    <a href="{{ route('admin.users.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">{!! $ic('user') !!} Pengguna & Hak Akses</a>
    <a href="{{ route('admin.audit-log.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.audit-log.*') ? $activeClass : $inactiveClass }}">{!! $ic('log') !!} Log Aktivitas</a>
    <a href="{{ route('admin.backup.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.backup.*') ? $activeClass : $inactiveClass }}">{!! $ic('backup') !!} Backup Database</a>
@endif

@if ($user->isGuruPiket() || $user->isAdmin())
    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Absensi</p>
    <a href="{{ route('piket.scan.index') }}" class="{{ $linkClass }} {{ request()->routeIs('piket.scan.*') ? $activeClass : $inactiveClass }}">{!! $ic('camera') !!} Scan Absensi</a>
    <a href="{{ route('piket.manual.index') }}" class="{{ $linkClass }} {{ request()->routeIs('piket.manual.*') ? $activeClass : $inactiveClass }}">{!! $ic('pencil') !!} Absensi Manual</a>
    <a href="{{ route('piket.rekap.harian') }}" class="{{ $linkClass }} {{ request()->routeIs('piket.rekap.harian') ? $activeClass : $inactiveClass }}">{!! $ic('list-check') !!} Rekap Harian</a>
    <a href="{{ route('piket.rekap.belum-hadir') }}" class="{{ $linkClass }} {{ request()->routeIs('piket.rekap.belum-hadir') ? $activeClass : $inactiveClass }}">{!! $ic('alert-circle') !!} Belum Hadir</a>
    <a href="{{ route('piket.rekap.keterlambatan') }}" class="{{ $linkClass }} {{ request()->routeIs('piket.rekap.keterlambatan') ? $activeClass : $inactiveClass }}">{!! $ic('clock') !!} Rekap Keterlambatan</a>
@endif

@if ($user->isGuruPiket() || $user->isGuruBk() || $user->isAdmin())
    <a href="{{ route('pelanggaran.index') }}" class="{{ $linkClass }} {{ request()->routeIs('pelanggaran.*') ? $activeClass : $inactiveClass }}">{!! $ic('ban') !!} Pelanggaran</a>
@endif

@if ($user->isGuruBk() || $user->isAdmin())
    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Bimbingan Konseling</p>
    <a href="{{ route('bk.index') }}" class="{{ $linkClass }} {{ request()->routeIs('bk.*') ? $activeClass : $inactiveClass }}">{!! $ic('heart') !!} Siswa Bermasalah</a>
@endif

@if (in_array($user->role->kode, ['admin', 'guru_piket', 'guru_bk', 'kepsek']))
    <p class="px-3 pt-5 pb-1.5 text-[12px] font-bold uppercase tracking-wider text-primary-200/90">Laporan</p>
    <a href="{{ route('laporan.index') }}" class="{{ $linkClass }} {{ request()->routeIs('laporan.*') ? $activeClass : $inactiveClass }}">{!! $ic('chart') !!} Pusat Laporan</a>
@endif

@if ($user->isOrtu())
    <a href="{{ route('ortu.portal') }}" class="{{ $linkClass }} {{ request()->routeIs('ortu.portal') ? $activeClass : $inactiveClass }}">{!! $ic('family') !!} Portal Anak Saya</a>
    <a href="{{ route('ortu.notifikasi') }}" class="{{ $linkClass }} {{ request()->routeIs('ortu.notifikasi') ? $activeClass : $inactiveClass }}">{!! $ic('bell') !!} Notifikasi</a>
@endif
