@extends('layouts.app')
@section('title', 'Belum Hadir')
@section('page-title', 'Siswa Belum Tercatat Hadir')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Siswa Belum Tercatat Hadir</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Daftar siswa yang belum melakukan absensi pada tanggal yang dipilih</p>
        </div>
    </div>

    {{-- Form Filter --}}
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 140px; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
            </div>
            
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 6px;">Kelas</label>
                <select name="kelas_id" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border: none; border-radius: 10px; padding: 11px 18px; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 14px 20px;">NIS</th>
                        <th style="padding: 14px 20px;">Nama</th>
                        <th style="padding: 14px 20px;">Kelas</th>
                    </tr>
                </thead>
                <tbody style="divide-y: 1px solid #f1f5f9;">
                    @forelse ($siswa as $s)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 14px 20px; font-weight: 600; color: #334155;">{{ $s->nis }}</td>
                            <td style="padding: 14px 20px; font-weight: 700; color: #0f172a;">{{ $s->nama }}</td>
                            <td style="padding: 14px 20px; font-weight: 500; color: #64748b;">{{ $s->kelas->nama }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 32px; text-align: center; color: #64748b; font-size: 13px;">
                                Semua siswa sudah tercatat hadir. 🎉
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 10px;">
        {{ $siswa->links() }}
    </div>
</div>
@endsection