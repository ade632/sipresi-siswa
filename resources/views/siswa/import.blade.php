@extends('layouts.app')
@section('title', 'Import Data Siswa')
@section('page-title', 'Import Data Siswa dari Excel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 1200px; margin: 0 auto;">

    <!-- HEADER / KEMBALI -->
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <a href="{{ route('admin.siswa.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #2563eb; text-decoration: none;">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Data Siswa
        </a>
    </div>

    <!-- FLASH MESSAGE / HASIL IMPORT -->
    @if(session('hasil_import'))
        @php
            $hasil = session('hasil_import');
            $jmlBerhasil = count($hasil['berhasil'] ?? []);
            $jmlGagal = count($hasil['gagal'] ?? []);
        @endphp
        <div style="background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <h3 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; color: #0f172a;">Hasil Proses Import</h3>
            <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                <div style="background-color: #dcfce7; color: #166534; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    ✅ Berhasil: {{ $jmlBerhasil }} baris
                </div>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    ❌ Gagal: {{ $jmlGagal }} baris
                </div>
            </div>

            @if($jmlGagal > 0)
                <div style="max-height: 200px; overflow-y: auto; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; font-size: 12px; color: #b91c1c;">
                    <strong>Detail Kegagalan:</strong>
                    <ul style="margin: 6px 0 0 20px; padding: 0;">
                        @foreach($hasil['gagal'] as $gagal)
                            <li>Baris {{ $gagal['baris'] ?? '-' }}: {{ $gagal['error'] ?? 'Kesalahan format data' }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- UTAMA: GRID 2 KOLOM -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
        
        <!-- KOLOM KIRI: FORM UPLOAD -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">Upload File Excel / CSV</h3>
                <p style="margin: 6px 0 0 0; font-size: 13px; color: #64748b; line-height: 1.5;">
                    Gunakan template resmi supaya kolom sesuai. Setiap baris akan otomatis membuat data siswa, akun orang tua (password default = NISN), dan kartu QR siswa.
                </p>
            </div>

            <form action="{{ route('admin.siswa.import.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                <!-- INPUT FILE YANG DIPERBAIKI AGAR MUDAH DIKLIK -->
                <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px; text-align: center; background-color: #f8fafc;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <svg style="width: 36px; height: 36px; color: #64748b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        
                        <input type="file" name="file" required accept=".xlsx, .xls, .csv" style="width: 100%; max-width: 280px; font-size: 12px; color: #334155; padding: 6px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer;">
                        
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Format: .xlsx, .xls, atau .csv — Maksimal 5MB</div>
                    </div>
                </div>

                @error('file')
                    <span style="font-size: 12px; color: #b91c1c; font-weight: 600;">{{ $message }}</span>
                @enderror

                <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; background-color: #2563eb; color: #ffffff; font-size: 13px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Proses Import Sekarang
                </button>
            </form>
        </div>

        <!-- KOLOM KANAN: PANDUAN & TEMPLATE -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #0f172a;">Template Excel</h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Unduh template resmi, isi datanya, lalu unggah kembali.</p>
                </div>
                <a href="{{ route('admin.siswa.import.template') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background-color: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none;">
                    ⬇️ Unduh Template
                </a>
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 0;">

            <div style="font-size: 13px; color: #334155; line-height: 1.6;">
                <div style="font-weight: 700; margin-bottom: 6px; color: #0f172a;">Kolom Wajib Diisi:</div>
                <ul style="margin: 0 0 12px 18px; padding: 0; color: #475569;">
                    <li><strong style="color: #0f172a;">nis, nisn</strong> — Harus unik, belum pernah dipakai</li>
                    <li><strong style="color: #0f172a;">nama</strong> — Nama lengkap siswa</li>
                    <li><strong style="color: #0f172a;">kelas</strong> — Harus persis sama dengan nama di menu Data Kelas (contoh: <code style="background-color: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px;">X TKJ 1</code>)</li>
                    <li><strong style="color: #0f172a;">jenis_kelamin</strong> — Isi <code style="background-color: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px;">L</code> atau <code style="background-color: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px;">P</code></li>
                    <li><strong style="color: #0f172a;">nama_ortu, email_ortu</strong> — Untuk akun Portal Orang Tua</li>
                </ul>

                <div style="font-weight: 700; margin-bottom: 6px; color: #0f172a;">Kolom Opsional:</div>
                <ul style="margin: 0 0 0 18px; padding: 0; color: #475569;">
                    <li><strong style="color: #0f172a;">tanggal_lahir</strong> (format: <code style="background-color: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 11px;">YYYY-MM-DD</code>)</li>
                    <li><strong style="color: #0f172a;">alamat, no_hp_ortu</strong></li>
                    <li><strong style="color: #0f172a;">rfid_uid</strong> (jika kartu RFID fisik sudah ada)</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection