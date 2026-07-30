@extends('layouts.app')
@section('title', 'Kalender Akademik')
@section('page-title', 'Kalender Akademik')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD (KONSISTEN DENGAN HALAMAN LAIN) -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Kalender Akademik</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Kelola tanggal libur nasional, libur sekolah, dan jadwal kegiatan penting sekolah</p>
        </div>
    </div>

    <!-- MAIN CONTENT GRID (TABEL & FORM) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
        
        <!-- TABLE CARD (KIRI - LEBIH LEBAR JIKA MEMUNGKINKAN) -->
        <div style="grid-column: span 2 / span 2; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 14px 20px;">Tanggal</th>
                            <th style="padding: 14px 20px; text-align: center;">Tipe</th>
                            <th style="padding: 14px 20px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody style="divide-y: 1px solid #f1f5f9;">
                        @forelse ($kalender as $k)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <!-- TANGGAL -->
                                <td style="padding: 14px 20px; vertical-align: middle; font-weight: 700; color: #0f172a; white-space: nowrap;">
                                    {{ $k->tanggal->format('d-m-Y') }}
                                </td>

                                <!-- TIPE BADGE -->
                                <td style="padding: 14px 20px; vertical-align: middle; text-align: center; white-space: nowrap;">
                                    @if($k->tipe === 'libur_nasional')
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #fee2e2; color: #b91c1c; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Libur Nasional
                                        </span>
                                    @elseif($k->tipe === 'libur_sekolah')
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Libur Sekolah
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; border-radius: 20px;">
                                            Kegiatan Sekolah
                                        </span>
                                    @endif
                                </td>

                                <!-- KETERANGAN -->
                                <td style="padding: 14px 20px; vertical-align: middle; color: #334155; font-weight: 500;">
                                    {{ $k->keterangan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                                    Belum ada data kalender akademik.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FORM CARD (KANAN) -->
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding-bottom: 14px; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Tambah Tanggal Penting</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Isi formulir untuk menambahkan agenda baru</p>
            </div>

            <form method="POST" action="{{ route('admin.kalender-akademik.store') }}" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                
                <!-- TAHUN AJARAN -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        @foreach ($tahunAjaran as $t)
                            <option value="{{ $t->id }}">{{ $t->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- TANGGAL -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Tanggal</label>
                    <input type="date" name="tanggal" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <!-- TIPE -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Tipe Agenda</label>
                    <select name="tipe" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="libur_nasional">Libur Nasional</option>
                        <option value="libur_sekolah">Libur Sekolah</option>
                        <option value="kegiatan_sekolah">Kegiatan Sekolah</option>
                    </select>
                </div>

                <!-- KETERANGAN -->
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Keterangan Agenda</label>
                    <input type="text" name="keterangan" placeholder="Contoh: HUT RI ke-81..." required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
                </div>

                <!-- TOMBOL SIMPAN -->
                <button type="submit" style="margin-top: 6px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Agenda
                </button>
            </form>
        </div>

    </div>
</div>
@endsection