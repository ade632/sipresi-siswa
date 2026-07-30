@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas')

@section('content')
<div style="max-width: 672px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">
    
    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="padding: 10px; background-color: rgba(37, 99, 235, 0.2); color: #60a5fa; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Tambah Kelas Baru</h2>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Lengkapi formulir untuk membuat rombongan belajar baru</p>
            </div>
        </div>
        <a href="{{ route('admin.kelas.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #e2e8f0; background-color: #1e293b; border: 1px solid #334155; padding: 8px 14px; border-radius: 8px; text-decoration: none;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- FORM UTAMA CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="POST" action="{{ route('admin.kelas.store') }}" style="margin: 0; display: flex; flex-direction: column; gap: 24px;">
            @csrf

            <!-- SEKSI 1: INFORMASI KELAS -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Informasi Kelas
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Nama Kelas <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: XII TKJ 1" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    @error('nama') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Tingkat <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="tingkat" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                            <option value="" disabled selected>-- Pilih Tingkat --</option>
                            <option value="10" @selected(old('tingkat') == '10' || old('tingkat') == 'X')>X (10)</option>
                            <option value="11" @selected(old('tingkat') == '11' || old('tingkat') == 'XI')>XI (11)</option>
                            <option value="12" @selected(old('tingkat') == '12' || old('tingkat') == 'XII')>XII (12)</option>
                        </select>
                        @error('tingkat') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Jurusan <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="jurusan_id" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                            <option value="" disabled selected>-- Pilih Jurusan --</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->id }}" @selected(old('jurusan_id') == $j->id)>{{ $j->nama }}</option>
                            @endforeach
                        </select>
                        @error('jurusan_id') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Tahun Ajaran <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="tahun_ajaran_id" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                        @foreach ($tahunAjaran as $t)
                            <option value="{{ $t->id }}" @selected(old('tahun_ajaran_id') == $t->id || (!old('tahun_ajaran_id') && ($t->is_aktif ?? false)))>
                                {{ $t->nama ?? $t->tahun_ajaran }} {{ isset($t->semester) ? '('.ucfirst($t->semester).')' : ($t->is_aktif ? '(Aktif)' : '') }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- SEKSI 2: WALI KELAS -->
            <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #fcd34d; padding-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Wali Kelas
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Nama Wali Kelas</label>
                    <input type="text" name="wali_kelas_nama" value="{{ old('wali_kelas_nama') }}" autocomplete="off" placeholder="Contoh: Dra. Siti Aminah, S.Pd." style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    <p style="font-size: 12px; color: #64748b; margin: 6px 0 0 0;">💡 Diisi manual — tidak perlu wali kelas punya akun login di sistem ini.</p>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Akun Sistem Wali Kelas <span style="font-weight: 400; color: #94a3b8; font-size: 10px;">(OPSIONAL)</span>
                    </label>
                    <select name="wali_kelas_id" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                        <option value="">-- Tidak ada / tidak punya akun --</option>
                        @foreach ($guru as $g)
                            <option value="{{ $g->id }}" @selected(old('wali_kelas_id') == $g->id)>{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <p style="font-size: 12px; color: #64748b; margin: 6px 0 0 0;">💡 Hanya isi jika wali kelas ini kebetulan juga terdaftar sebagai Guru Piket/BK di sistem.</p>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('admin.kelas.index') }}" style="padding: 10px 20px; font-size: 13px; font-weight: 600; color: #334155; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; text-decoration: none;">
                    Batal
                </a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection