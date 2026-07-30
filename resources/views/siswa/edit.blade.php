@extends('layouts.app')
@section('title', 'Ubah Data Siswa')
@section('page-title', 'Ubah Data Siswa')

@section('content')
<div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">
    
    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="padding: 10px; background-color: rgba(217, 119, 6, 0.2); color: #fbbf24; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Ubah Data Siswa</h2>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Perbarui rincian informasi data diri, kelas, RFID, atau kontak ortu</p>
            </div>
        </div>
        <a href="{{ route('admin.siswa.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #e2e8f0; background-color: #1e293b; border: 1px solid #334155; padding: 8px 14px; border-radius: 8px; text-decoration: none;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- FORM UTAMA CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}" enctype="multipart/form-data" style="margin: 0; display: flex; flex-direction: column; gap: 20px;">
            @csrf
            @method('PUT')

            <!-- SEKSI 1: DATA PRIBADI SISWA -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Data Pribadi Siswa
                </div>

                <!-- NIS & NISN -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            NIS <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                        @error('nis') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            NISN <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                        @error('nisn') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- NAMA LENGKAP -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Nama Lengkap <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    @error('nama') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                </div>

                <!-- KELAS & JENIS KELAMIN -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Kelas <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="kelas_id" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Jenis Kelamin <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="jenis_kelamin" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                            <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' || old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' || old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- TANGGAL LAHIR, STATUS & FOTO SISWA -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Status Siswa <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" required style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; cursor: pointer;">
                            <option value="aktif" @selected(old('status', $siswa->status) == 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $siswa->status) == 'nonaktif')>Nonaktif</option>
                            <option value="lulus" @selected(old('status', $siswa->status) == 'lulus')>Lulus</option>
                            <option value="pindah" @selected(old('status', $siswa->status) == 'pindah')>Pindah</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                            Ganti Foto <span style="font-weight: 400; color: #94a3b8; font-size: 10px;">(OPSIONAL)</span>
                        </label>
                        <input type="file" name="foto" accept="image/*" style="width: 100%; padding: 8px 12px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; box-sizing: border-box; outline: none;">
                    </div>
                </div>

                <!-- ALAMAT LENGKAP -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Alamat Lengkap
                    </label>
                    <textarea name="alamat" rows="2" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; resize: vertical;">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
            </div>

            <!-- SEKSI 2: KARTU AKSES ABSENSI -->
            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #86efac; padding-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Kartu Akses Absensi
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        UID Kartu RFID
                    </label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $siswa->rfid_uid) }}" placeholder="Tempelkan kartu RFID ke reader untuk mengisi otomatis" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                </div>
            </div>

            <!-- SEKSI 3: AKUN ORANG TUA / WALI -->
            <div style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #fcd34d; padding-bottom: 8px;">
                    <svg style="width: 16px; height: 16px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Akun Orang Tua / Wali
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua', $siswa->nama_orang_tua ?? $siswa->orang_tua) }}" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">No. HP Orang Tua (WhatsApp)</label>
                        <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu ?? $siswa->hp_ortu) }}" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Email Orang Tua
                    </label>
                    <input type="email" name="email_ortu" value="{{ old('email_ortu', $siswa->email_ortu ?? $siswa->userOrtu->email ?? '') }}" style="width: 100%; padding: 10px 14px; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none;">
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('admin.siswa.index') }}" style="padding: 10px 20px; font-size: 13px; font-weight: 600; color: #334155; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; text-decoration: none;">
                    Batal
                </a>
                <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection