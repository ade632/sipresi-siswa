@extends('layouts.app')
@section('title', 'Siswa Bermasalah')
@section('page-title', 'Monitoring Siswa Bermasalah')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Monitoring Siswa Bermasalah</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Daftar siswa dengan poin pelanggaran signifikan (≥ 15)</p>
        </div>
    </div>

    {{-- Daftar Siswa Bermasalah --}}
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
            <h3 style="font-weight: 700; color: #0f172a; font-size: 14px; margin: 0;">Daftar Siswa dengan Poin Signifikan (≥ 15)</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse ($siswaBermasalah as $siswa)
                @php
                    $status = $siswa->total_poin >= 75 
                        ? ['Perlu Pembinaan Khusus', 'background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;']
                        : ($siswa->total_poin >= 40 
                            ? ['Cukup', 'background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a;'] 
                            : ['Baik', 'background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe;']);
                @endphp
                <a href="{{ route('bk.detail', $siswa) }}" style="padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; text-decoration: none; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                    <div>
                        <p style="font-weight: 700; color: #0f172a; margin: 0;" class="hover:text-blue-600">{{ $siswa->nama }}</p>
                        <p style="color: #64748b; font-size: 12px; margin: 3px 0 0 0;">{{ $siswa->kelas->nama }} · NIS {{ $siswa->nis }}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-weight: 700; color: #0f172a; margin: 0;">{{ $siswa->total_poin }} poin</p>
                        <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 4px; {{ $status[1] }}">
                            {{ $status[0] }}
                        </span>
                    </div>
                </a>
            @empty
                <div style="padding: 48px 20px; text-align: center; color: #64748b; font-size: 13px;">
                    Tidak ada siswa dengan poin signifikan saat ini. 🎉
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection