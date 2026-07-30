@extends('layouts.app')
@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD (KONSISTEN DENGAN DATA SISWA & DASHBOARD) -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Data Kelas</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Kelola daftar kelas, jurusan, wali kelas, dan cetak kartu siswa per kelas</p>
        </div>
        <div>
            <a href="{{ route('admin.kelas.create') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border-radius: 10px; padding: 10px 18px; text-decoration: none; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas
            </a>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 14px 20px;">Nama Kelas</th>
                        <th style="padding: 14px 20px;">Jurusan</th>
                        <th style="padding: 14px 20px;">Tahun Ajaran</th>
                        <th style="padding: 14px 20px;">Wali Kelas</th>
                        <th style="padding: 14px 20px; text-align: center;">Jml Siswa</th>
                        <th style="padding: 14px 20px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="divide-y: 1px solid #f1f5f9;">
                    @forelse ($kelas as $k)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <!-- NAMA KELAS -->
                            <td style="padding: 14px 20px; vertical-align: middle; font-weight: 700; color: #0f172a; font-size: 14px;">
                                {{ $k->nama }}
                            </td>

                            <!-- JURUSAN -->
                            <td style="padding: 14px 20px; vertical-align: middle; color: #334155; font-weight: 500;">
                                {{ $k->jurusan->nama ?? '-' }}
                            </td>

                            <!-- TAHUN AJARAN -->
                            <td style="padding: 14px 20px; vertical-align: middle; color: #64748b; font-weight: 500;">
                                {{ $k->tahunAjaran->nama ?? '-' }}
                            </td>

                            <!-- WALI KELAS -->
                            <td style="padding: 14px 20px; vertical-align: middle; color: #334155; font-weight: 500;">
                                {{ $k->namaWaliKelas() }}
                            </td>

                            <!-- JUMLAH SISWA -->
                            <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                <span style="display: inline-block; padding: 4px 12px; background-color: #f1f5f9; color: #0f172a; font-size: 12px; font-weight: 700; border-radius: 20px;">
                                    {{ $k->siswa_count }}
                                </span>
                            </td>

                            <!-- AKSI BADGES (SERAGAM DENGAN DATA SISWA) -->
                            <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 6px; flex-wrap: wrap;">
                                    
                                    <!-- CETAK KARTU BATCH -->
                                    <a href="{{ route('admin.siswa.cetak-kartu-batch', $k) }}" target="_blank" title="Cetak Semua Kartu Siswa di Kelas Ini" style="padding: 6px 10px; background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        🖨️ Cetak Kartu
                                    </a>

                                    <!-- UBAH -->
                                    <a href="{{ route('admin.kelas.edit', $k) }}" style="padding: 6px 12px; background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                        Ubah
                                    </a>

                                    <!-- HAPUS -->
                                    <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                                Belum ada data kelas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINASI / FOOTER -->
        @if(method_exists($kelas, 'hasPages') && $kelas->hasPages())
            <div style="padding: 16px 20px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection