@extends('layouts.app')
@section('title', 'Data Jurusan')
@section('page-title', 'Data Jurusan')

@section('content')
<div style="width: 100%; max-width: 1280px; margin: 0 auto; padding: 16px; box-sizing: border-box;">
    
    <!-- GRID CONTAINER UTAMA -->
    <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start; width: 100%;">
        
        <!-- ================= KOLOM KIRI: TABEL DATA JURUSAN (LEBAR 2/3) ================= -->
        <div style="flex: 2 1 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05); overflow: hidden;">
            
            <!-- Header Box Kiri -->
            <div style="background-color: #0f172a; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="padding: 8px; background-color: rgba(37, 99, 235, 0.2); color: #60a5fa; border-radius: 10px;">
                        <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Daftar Jurusan</h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Kelola program dan kompetensi keahlian sekolah</p>
                    </div>
                </div>
                <span style="padding: 4px 12px; background-color: rgba(59, 130, 246, 0.2); color: #93c5fd; font-size: 12px; font-weight: 600; border-radius: 9999px; border: 1px solid rgba(59, 130, 246, 0.3);">
                    Total: {{ count($jurusan) }}
                </span>
            </div>

            <!-- Area Tabel -->
            <div style="overflow-x: auto; width: 100%;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 14px 20px;">Kode</th>
                            <th style="padding: 14px 20px;">Nama Jurusan</th>
                            <th style="padding: 14px 16px; text-align: center;">Jml Kelas</th>
                            <th style="padding: 14px 20px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #334155;">
                        @forelse ($jurusan as $j)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                
                                <!-- Kode Jurusan Badge -->
                                <td style="padding: 16px 20px; white-space: nowrap;">
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #f1f5f9; color: #1e293b; font-weight: 700; font-size: 12px; border-radius: 6px; border: 1px solid #cbd5e1;">
                                        {{ $j->kode }}
                                    </span>
                                </td>
                                
                                <!-- Nama Jurusan -->
                                <td style="padding: 16px 20px; font-weight: 700; color: #0f172a;">
                                    {{ $j->nama }}
                                </td>
                                
                                <!-- Jumlah Kelas Badge -->
                                <td style="padding: 16px 16px; text-align: center; white-space: nowrap;">
                                    <span style="display: inline-block; padding: 4px 12px; background-color: #eff6ff; color: #2563eb; font-weight: 600; font-size: 12px; border-radius: 9999px; border: 1px solid #bfdbfe;">
                                        {{ $j->kelas_count ?? $j->kelas()->count() ?? 0 }} Kelas
                                    </span>
                                </td>
                                
                                <!-- Tombol Aksi -->
                                <td style="padding: 16px 20px; text-align: center; white-space: nowrap;">
                                    <div style="display: flex; items-center: center; justify-content: center; gap: 8px;">
                                        <form action="{{ route('admin.jurusan.destroy', $j) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')" style="margin: 0; display: inline-block;">
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
                                <td colspan="4" style="padding: 48px 20px; text-align: center; color: #64748b; font-size: 14px;">
                                    <p style="margin: 0;">Belum ada data jurusan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: FORM TAMBAH JURUSAN (LEBAR 1/3) ================= -->
        <div style="flex: 1 1 320px; max-width: 400px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05); overflow: hidden;">
            
            <!-- Header Box Form -->
            <div style="background-color: #0f172a; padding: 18px 24px; border-bottom: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="padding: 8px; background-color: rgba(16, 185, 129, 0.2); color: #34d399; border-radius: 10px;">
                        <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700; color: #ffffff; line-height: 1.2;">Tambah Jurusan</h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #94a3b8;">Input data jurusan baru</p>
                    </div>
                </div>
            </div>

            <!-- Body Form -->
            <form method="POST" action="{{ route('admin.jurusan.store') }}" style="padding: 24px; display: flex; flex-direction: column; gap: 16px; margin: 0;">
                @csrf

                <!-- Input Kode Jurusan -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Kode Jurusan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="kode" value="{{ old('kode') }}" required placeholder="Contoh: TKJ, AKL" 
                        style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                    @error('kode') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Input Nama Jurusan -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Nama Jurusan <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Teknik Komputer dan Jaringan" 
                        style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; color: #0f172a; font-weight: 500; box-sizing: border-box; outline: none;">
                    @error('nama') 
                        <p style="font-size: 12px; color: #dc2626; margin: 4px 0 0 0; font-weight: 500;">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div style="padding-top: 8px;">
                    <button type="submit" style="width: 100%; padding: 12px; background-color: #2563eb; color: #ffffff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Simpan Jurusan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection