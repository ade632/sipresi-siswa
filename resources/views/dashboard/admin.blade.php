@extends('layouts.app')
@section('title', 'Dashboard Administrator')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD (KONSISTEN DENGAN HALAMAN DATA SISWA) -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">
                Selamat Datang, Administrator 👋
            </h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">
                Ringkasan statistik kehadiran siswa dan aktivitas pelanggaran disiplin secara terintegrasi.
            </p>
        </div>
        
        <!-- Tanggal Badge -->
        <div style="display: flex; align-items: center; gap: 10px; background-color: #1e293b; border: 1px solid #334155; padding: 8px 14px; border-radius: 10px;">
            <svg style="width: 18px; height: 18px; color: #38bdf8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span style="font-size: 13px; font-weight: 600; color: #f8fafc;">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- STATISTIK UTAMA (4 KOLOM) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        
        <!-- Total Siswa Aktif -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; items-center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Total Siswa Aktif</p>
                <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #0f172a;">{{ $totalSiswa ?? 13 }}</h3>
                <span style="font-size: 11px; font-weight: 600; color: #16a34a; margin-top: 4px; display: inline-block;">Terdaftar Aktif</span>
            </div>
            <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            </div>
        </div>

        <!-- Hadir Hari Ini -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; items-center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Hadir Hari Ini</p>
                <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #0f172a;">{{ $totalHadir ?? 0 }}</h3>
                <span style="font-size: 11px; font-weight: 600; color: #64748b; margin-top: 4px; display: inline-block;">Presensi Masuk</span>
            </div>
            <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Terlambat -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; items-center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Terlambat</p>
                <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #0f172a;">{{ $totalTerlambat ?? 0 }}</h3>
                <span style="font-size: 11px; font-weight: 600; color: #d97706; margin-top: 4px; display: inline-block;">Butuh Catatan</span>
            </div>
            <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Alpa -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; items-center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">Alpa</p>
                <h3 style="margin: 6px 0 0 0; font-size: 24px; font-weight: 800; color: #0f172a;">{{ $totalAlpa ?? 13 }}</h3>
                <span style="font-size: 11px; font-weight: 600; color: #dc2626; margin-top: 4px; display: inline-block;">Tanpa Keterangan</span>
            </div>
            <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

    </div>

    <!-- STATISTIK SEKUNDER (4 KOLOM) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
        
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94a3b8;">Izin</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">{{ $totalIzin ?? 0 }}</p>
            </div>
            <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #f8fafc; color: #475569; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94a3b8;">Sakit</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">{{ $totalSakit ?? 0 }}</p>
            </div>
            <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #f8fafc; color: #475569; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94a3b8;">Dispensasi</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">{{ $totalDispensasi ?? 0 }}</p>
            </div>
            <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #f8fafc; color: #475569; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="margin: 0; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94a3b8;">Piket Aktif</p>
                <p style="margin: 4px 0 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">{{ $totalPiket ?? 1 }}</p>
            </div>
            <div style="width: 36px; height: 36px; border-radius: 8px; background-color: #f8fafc; color: #475569; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>

    </div>

    <!-- PELANGGARAN TERBARU CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);"
         x-data="{ 
             pelanggaran: [],
             loading: true,
             async loadPelanggaran() {
                 try {
                     let response = await fetch('/admin/api/pelanggaran-terbaru');
                     if(response.ok) {
                         this.pelanggaran = await response.json();
                     }
                 } catch (e) {
                     console.error('Gagal memuat data pelanggaran:', e);
                 } finally {
                     this.loading = false;
                 }
             }
         }"
         x-init="loadPelanggaran(); setInterval(() => loadPelanggaran(), 5000)">
        
        <!-- Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background-color: #ffffff;">
            <div>
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Pelanggaran Terbaru</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Catatan pelanggaran disiplin siswa terbaru secara real-time</p>
            </div>
            <a href="{{ route('pelanggaran.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #2563eb; text-decoration: none;">
                Lihat Semua
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- List Items -->
        <div>
            <template x-for="item in pelanggaran" :key="item.id">
                <div style="padding: 14px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #eff6ff; color: #2563eb; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 13px; border: 1px solid #bfdbfe;"
                             x-text="item.nama_siswa ? item.nama_siswa.substring(0, 1).toUpperCase() : 'S'">
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #0f172a; font-size: 14px;" x-text="item.nama_siswa"></div>
                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;" x-text="item.nama_pelanggaran"></div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: inline-block; padding: 4px 10px; background-color: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700; border-radius: 20px;"
                              x-text="item.poin + ' Poin'">
                        </span>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;" x-text="item.tanggal"></div>
                    </div>
                </div>
            </template>

            <template x-if="!loading && pelanggaran.length === 0">
                <div style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                    Belum ada catatan pelanggaran terbaru hari ini.
                </div>
            </template>
        </div>
    </div>

</div>
@endsection