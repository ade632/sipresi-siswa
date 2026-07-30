@extends('layouts.app')
@section('title', 'Jenis Pelanggaran')
@section('page-title', 'Jenis Pelanggaran & Poin')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Jenis Pelanggaran & Poin</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Kelola kategori pelanggaran dan bobot poin sanksi siswa</p>
        </div>
    </div>

    <!-- MAIN GRID CONTAINER -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
        
        <!-- KOLOM KIRI: TABEL DAFTAR PELANGGARAN -->
        <div style="grid-column: span 2 / span 2; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Daftar Jenis Pelanggaran</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Rincian kategori dan bobot poin pelanggaran disiplin</p>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 14px 20px;">Nama Pelanggaran</th>
                            <th style="padding: 14px 20px; text-align: center;">Kategori</th>
                            <th style="padding: 14px 20px; text-align: center;">Poin</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid #f1f5f9;">
                        @forelse ($jenisPelanggaran as $j)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <!-- NAMA PELANGGARAN -->
                                <td style="padding: 14px 20px; vertical-align: middle; font-weight: 700; color: #0f172a;">
                                    {{ $j->nama }}
                                </td>

                                <!-- KATEGORI BADGE -->
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                    @if($j->kategori === 'berat')
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #fee2e2; color: #b91c1c; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Berat
                                        </span>
                                    @elseif($j->kategori === 'sedang')
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Sedang
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Ringan
                                        </span>
                                    @endif
                                </td>

                                <!-- POIN -->
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: center; font-weight: 700; color: #0f172a;">
                                    {{ $j->poin }}
                                </td>

                                <!-- AKSI -->
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: right;">
                                    <form action="{{ route('admin.jenis-pelanggaran.destroy', $j) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pelanggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding: 6px 12px; background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                                    Belum ada data jenis pelanggaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM TAMBAH JENIS PELANGGARAN -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding-bottom: 14px; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Tambah Pelanggaran</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Input data dan bobot poin sanksi</p>
            </div>

            <form method="POST" action="{{ route('admin.jenis-pelanggaran.store') }}" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                
                <!-- NAMA PELANGGARAN -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">
                        Nama Pelanggaran <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="nama" placeholder="Contoh: Membolos jam pelajaran" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <!-- KATEGORI -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">
                        Kategori <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="kategori" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="" disabled selected style="color: #94a3b8;">Pilih Kategori</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="berat">Berat</option>
                    </select>
                </div>

                <!-- POIN -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">
                        Poin (1-100) <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="number" name="poin" placeholder="Contoh: 10" min="1" max="100" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <!-- TOMBOL SIMPAN -->
                <button type="submit" style="margin-top: 6px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Jenis Pelanggaran
                </button>
            </form>
        </div>

    </div>
</div>
@endsection