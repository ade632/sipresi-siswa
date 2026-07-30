@extends('layouts.app')
@section('title', 'Perangkat Piket')
@section('page-title', 'Manajemen Perangkat Piket')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD (KONSISTEN DENGAN HALAMAN LAIN) -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Manajemen Perangkat Piket</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Pantau dan kelola perangkat atau pos scan absensi yang terhubung ke sistem</p>
        </div>
    </div>

    <!-- KOTAK INFORMASI -->
    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 18px 24px; color: #1e40af; font-size: 13px; line-height: 1.5; display: flex; gap: 14px; align-items: flex-start; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <span style="font-size: 18px; line-height: 1;">ℹ️</span>
        <div>
            Device Guru Piket kini <strong>otomatis terdaftar</strong> saat pertama kali dipakai untuk scan absensi — tidak perlu didaftarkan manual dulu. Halaman ini digunakan untuk memantau & menonaktifkan device tertentu bila diperlukan (misalnya device yang hilang atau tidak seharusnya dipakai piket)[cite: 9].
        </div>
    </div>

    <!-- UTAMA: GRID (Tabel di kiri, Form di kanan) -->
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start;">
        
        <!-- KOLOM KIRI: TABEL PERANGKAT -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 18px 24px; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Daftar Perangkat Terdaftar</h3>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 14px 20px;">Nama Device</th>
                            <th style="padding: 14px 20px;">Lokasi Pos</th>
                            <th style="padding: 14px 20px; text-align: center;">Status</th>
                            <th style="padding: 14px 20px;">Terakhir Dipakai</th>
                            <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid #f1f5f9;">
                        @forelse($perangkatPikets ?? [] as $device)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 14px 20px; vertical-align: middle; font-weight: 700; color: #0f172a;">{{ $device->nama_device }}</td>
                                <td style="padding: 14px 20px; vertical-align: middle; color: #334155; font-weight: 500;">{{ $device->lokasi_pos ?? '-' }}</td>
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; border-radius: 20px;">Aktif</span>
                                </td>
                                <td style="padding: 14px 20px; vertical-align: middle; color: #64748b; font-size: 12px;">{{ $device->updated_at->diffForHumans() }}</td>
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: right; white-space: nowrap;">
                                    <a href="#" style="padding: 6px 10px; background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; margin-right: 6px; display: inline-block;">Nonaktifkan</a>
                                    <a href="#" style="padding: 6px 12px; background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block;">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                                    Belum ada perangkat terdaftar[cite: 9]. Perangkat akan otomatis masuk saat digunakan scan[cite: 9].
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM PENDAFTARAN MANUAL -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 16px;">
            <div style="padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Daftar Device Lebih Awal</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b; line-height: 1.4;">
                    Tidak wajib — hanya berguna kalau Anda ingin memberi nama device tertentu sebelumnya[cite: 9].
                </p>
            </div>

            <form action="#" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Nama Device</label>
                    <input type="text" name="nama_device" placeholder="Contoh: Tablet Pos Utama" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Lokasi Pos (Opsional)</label>
                    <input type="text" name="lokasi_pos" placeholder="Contoh: Gerbang Depan" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <button type="button" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #334155; background-color: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 16px; cursor: pointer;">
                    📱 Ambil Fingerprint Device Ini
                </button>

                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    Daftarkan Perangkat
                </button>
            </form>
        </div>

    </div>
</div>
@endsection