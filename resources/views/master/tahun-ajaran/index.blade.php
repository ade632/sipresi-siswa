@extends('layouts.app')
@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')

@section('content')
<div style="width: 100%; max-width: 1280px; margin: 0 auto; padding: 16px; box-sizing: border-box;">
    
    <!-- CONTAINER UTAMA: KIRI 68%, KANAN 30% DENGAN GAP SEIMPANG -->
    <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start; width: 100%;">
        
        <!-- ================= KOLOM KIRI: TABEL DATA TAHUN AJARAN ================= -->
        <div style="flex: 2 1 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); overflow: hidden;">
            
            <!-- Header Box (Header dibuat aman & tegas) -->
            <div style="background-color: #0f172a; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="padding: 8px; background-color: rgba(37, 99, 235, 0.2); color: #60a5fa; border-radius: 10px;">
                        <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Daftar Tahun Ajaran</h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Pengaturan periode akademik dan semester aktif</p>
                    </div>
                </div>
                <span style="padding: 4px 12px; background-color: rgba(59, 130, 246, 0.2); color: #93c5fd; font-size: 12px; font-weight: 600; border-radius: 9999px; border: 1px solid rgba(59, 130, 246, 0.3);">
                    Total: {{ count($tahunAjaran) }}
                </span>
            </div>

            <!-- Area Tabel -->
            <div style="overflow-x: auto; width: 100%;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 14px 20px;">Tahun Ajaran</th>
                            <th style="padding: 14px 16px;">Semester</th>
                            <th style="padding: 14px 20px;">Periode</th>
                            <th style="padding: 14px 16px; text-align: center;">Status</th>
                            <th style="padding: 14px 20px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #334155;">
                        @forelse ($tahunAjaran as $ta)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                
                                <!-- Tahun Ajaran (Multi-fallback agar tidak pernah kosong/hilang) -->
                                <td style="padding: 16px 20px; font-weight: 800; color: #0f172a; font-size: 14px; white-space: nowrap;">
                                    {{ $ta->tahun_ajaran ?? $ta->tahun ?? $ta->nama ?? 'Periode Baru' }}
                                </td>
                                
                                <!-- Semester -->
                                <td style="padding: 16px 16px; font-weight: 600; color: #1e293b; white-space: nowrap;">
                                    {{ ucfirst($ta->semester) }}
                                </td>
                                
                                <!-- Periode -->
                                <td style="padding: 16px 20px; font-size: 12px; font-weight: 500; color: #475569; white-space: nowrap;">
                                    {{ \Carbon\Carbon::parse($ta->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($ta->tanggal_selesai)->format('d M Y') }}
                                </td>
                                
                                <!-- Status -->
                                <td style="padding: 16px 16px; text-align: center; white-space: nowrap;">
                                    @if ($ta->is_aktif)
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">
                                            <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #10b981;"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span style="display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Aksi -->
                                <td style="padding: 16px 20px; text-align: center; white-space: nowrap;">
                                    <div style="display: flex; items-center: center; justify-content: center; gap: 8px;">
                                        @if(!$ta->is_aktif)
                                            <form action="{{ route('admin.tahun-ajaran.aktifkan', $ta) }}" method="POST" style="margin: 0; display: inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" style="font-size: 12px; font-weight: 600; color: #2563eb; background-color: #eff6ff; padding: 6px 12px; border-radius: 8px; border: 1px solid #bfdbfe; cursor: pointer;">
                                                    Set Aktif
                                                </button>
                                            </form>
                                        @else
                                            <span style="font-size: 12px; font-weight: 500; color: #94a3b8; font-style: italic; padding: 0 4px;">Sedang Berjalan</span>
                                        @endif
                                        
                                        <form action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')" style="margin: 0; display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="font-size: 12px; font-weight: 600; color: #dc2626; background-color: #fef2f2; padding: 6px 12px; border-radius: 8px; border: 1px solid #fecaca; cursor: pointer;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 48px 20px; text-align: center; color: #64748b; font-size: 14px;">
                                    <p style="margin: 0;">Belum ada data tahun ajaran.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: FORM TAMBAH TAHUN AJARAN ================= -->
        <div style="flex: 1 1 320px; max-width: 400px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); overflow: hidden;">
            
            <!-- Header Box Form -->
            <div style="background-color: #0f172a; padding: 18px 24px; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="padding: 8px; background-color: rgba(16, 185, 129, 0.2); color: #34d399; border-radius: 10px;">
                        <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Tambah Tahun Ajaran</h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Input periode akademik baru</p>
                    </div>
                </div>
            </div>

            <!-- Body Form -->
            <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}" style="padding: 24px; display: flex; flex-direction: column; gap: 16px; margin: 0;">
                @csrf

                <!-- Input Tahun Ajaran -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Tahun Ajaran <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}" required placeholder="Contoh: 2026/2027" 
                        style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                    @error('tahun_ajaran') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Semester -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Semester <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="semester" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                        <option value="ganjil" @selected(old('semester') === 'ganjil')>Ganjil</option>
                        <option value="genap" @selected(old('semester') === 'genap')>Genap</option>
                    </select>
                    @error('semester') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Tanggal Mulai -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Tanggal Mulai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required 
                        style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                    @error('tanggal_mulai') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Tanggal Selesai -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Tanggal Selesai <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required 
                        style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                    @error('tanggal_selesai') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div style="padding-top: 8px;">
                    <button type="submit" style="width: 100%; padding: 12px; background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Simpan Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection