@extends('layouts.app')
@section('title', 'Absensi Manual')
@section('page-title', 'Input Absensi Manual')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Input Absensi Manual</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Catat kehadiran siswa secara individual atau massal per kelas</p>
        </div>
    </div>

    <!-- MAIN GRID CONTAINER -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; align-items: start;">
        
        {{-- Form Input per Siswa --}}
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Input per Siswa</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Catat kehadiran, izin, atau sakit untuk siswa secara individual.</p>
            </div>

            <form method="GET" style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Pilih Kelas</label>
                <select name="kelas_id" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </form>

            <form method="POST" action="{{ route('piket.manual.store') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Siswa <span style="color: #dc2626;">*</span></label>
                    <select name="siswa_id" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Status Kehadiran <span style="color: #dc2626;">*</span></label>
                    <select name="status" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="dispensasi">Dispensasi</option>
                        <option value="alpa">Alpa</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Alasan izin/sakit/dispensasi, atau catatan kartu hilang/rusak..." style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; resize: none; box-sizing: border-box;"></textarea>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Lampiran (surat izin/dokter, opsional)</label>
                    <input type="file" name="lampiran" accept="image/*,.pdf" style="width: 100%; padding: 8px 12px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 12px; color: #475569; cursor: pointer; box-sizing: border-box;">
                </div>

                <button type="submit" style="margin-top: 6px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    Simpan Absensi
                </button>
            </form>
        </div>

        {{-- Form Input Massal per Kelas --}}
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding-bottom: 16px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Input Massal per Kelas</h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Cocok untuk kegiatan kolektif (study tour, lomba, dsb) dimana 1 kelas penuh perlu ditandai izin/sakit/dispensasi sekaligus.</p>
            </div>

            <form method="POST" action="{{ route('piket.manual.massal') }}" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Kelas <span style="color: #dc2626;">*</span></label>
                    <select name="kelas_id" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Status <span style="color: #dc2626;">*</span></label>
                    <select name="status" x-data x-on:change="$refs.keteranganMassal.required = $event.target.value !== 'hadir'" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="hadir">Hadir (Semua Hadir — misal ikut upacara/apel bersama)</option>
                        <option value="dispensasi">Dispensasi / Kegiatan Khusus (lomba, study tour, dll)</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Keterangan</label>
                    <textarea name="keterangan" x-ref="keteranganMassal" rows="2" placeholder="Contoh: Mengikuti Lomba LKS Tingkat Provinsi" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; resize: none; box-sizing: border-box;"></textarea>
                    <p style="font-size: 11px; color: #64748b; margin: 4px 0 0 0;">Opsional untuk status "Hadir".</p>
                </div>

                <button type="submit" style="margin-top: 14px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    Simpan untuk Seluruh Kelas
                </button>
            </form>
        </div>

    </div>
</div>
@endsection