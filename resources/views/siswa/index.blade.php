@extends('layouts.app')
@section('title', 'Daftar Data Siswa')
@section('page-title', 'Daftar Data Siswa')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- HEADER CARD -->
    <div style="background-color: #0f172a; border: 1px solid #1e293b; border-radius: 16px; padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff; line-height: 1.2;">Daftar Data Siswa</h2>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #94a3b8;">Kelola informasi siswa, filter kelas, cetak kartu QR, dan pendaftaran baru</p>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <!-- TOMBOL HAPUS PER KELAS -->
            <button type="button" onclick="document.getElementById('modalHapusKelas').style.display='flex'" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #dc2626; border-radius: 10px; padding: 10px 18px; text-decoration: none; border: none; cursor: pointer; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Per Kelas
            </button>

            <a href="{{ route('admin.siswa.create') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background-color: #2563eb; border-radius: 10px; padding: 10px 18px; text-decoration: none; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>
            @if(Route::has('admin.siswa.import'))
            <a href="{{ route('admin.siswa.import') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #334155; background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 16px; text-decoration: none;">
                <svg style="width: 16px; height: 16px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Excel
            </a>
            @endif
        </div>
    </div>

    <!-- FILTER & SEARCH CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('admin.siswa.index') }}" style="margin: 0; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            
            <!-- SEARCH INPUT -->
            <div style="flex: 1; min-width: 260px; position: relative;">
                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; display: flex;">
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIS / NISN..." style="width: 100%; padding: 10px 14px 10px 38px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; box-sizing: border-box;">
            </div>

            <!-- FILTER KELAS -->
            <div style="min-width: 160px;">
                <select name="kelas_id" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- FILTER STATUS -->
            <div style="min-width: 150px;">
                <select name="status" style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') == 'nonaktif')>Nonaktif</option>
                    <option value="lulus" @selected(request('status') == 'lulus')>Lulus</option>
                    <option value="pindah" @selected(request('status') == 'pindah')>Pindah</option>
                </select>
            </div>

            <!-- TOMBOL FILTER & RESET -->
            <button type="submit" style="padding: 10px 20px; background-color: #0f172a; color: #ffffff; font-size: 13px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer;">
                Filter
            </button>
            @if(request()->hasAny(['search', 'kelas_id', 'status']))
                <a href="{{ route('admin.siswa.index') }}" style="padding: 10px 16px; background-color: #f1f5f9; color: #64748b; font-size: 13px; font-weight: 600; border: 1px solid #cbd5e1; border-radius: 10px; text-decoration: none;">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABLE CARD -->
    <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 14px 20px;">NIS / NISN</th>
                        <th style="padding: 14px 20px;">Nama Siswa</th>
                        <th style="padding: 14px 20px;">Kelas</th>
                        <th style="padding: 14px 20px;">Orang Tua / Wali</th>
                        <th style="padding: 14px 20px; text-align: center;">Status</th>
                        <th style="padding: 14px 20px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="divide-y: 1px solid #f1f5f9;">
                    @forelse ($siswa as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <!-- NIS / NISN -->
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $item->nis }}</div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $item->nisn ?? '-' }}</div>
                            </td>

                            <!-- NAMA SISWA & FOTO -->
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if(!empty($item->foto))
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0;">
                                    @else
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background-color: #eff6ff; color: #2563eb; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 1px solid #bfdbfe;">
                                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 14px;">{{ $item->nama }}</div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                            {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- KELAS -->
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <div style="font-weight: 600; color: #1e293b;">{{ $item->kelas->nama ?? '-' }}</div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $item->kelas->jurusan->nama ?? $item->kelas->jurusan ?? '' }}</div>
                            </td>

                            <!-- ORANG TUA / WALI -->
                            <td style="padding: 14px 20px; vertical-align: middle;">
                                <div style="color: #334155; font-weight: 500;">
                                    {{ $item->orangTua->name ?? '-' }}
                                </div>
                                @if($item->orangTua && $item->orangTua->no_hp)
                                    <div style="font-size: 11px; color: #16a34a; margin-top: 2px; font-weight: 600;">
                                        💬 {{ $item->orangTua->no_hp }}
                                    </div>
                                @endif
                            </td>

                            <!-- STATUS -->
                            <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                @if(strtolower($item->status ?? 'aktif') == 'aktif')
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; border-radius: 20px;">Aktif</span>
                                @elseif(strtolower($item->status) == 'lulus')
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; border-radius: 20px;">Lulus</span>
                                @elseif(strtolower($item->status) == 'pindah')
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #fef3c7; color: #b45309; font-size: 11px; font-weight: 700; border-radius: 20px;">Pindah</span>
                                @else
                                    <span style="display: inline-block; padding: 4px 10px; background-color: #fee2e2; color: #b91c1c; font-size: 11px; font-weight: 700; border-radius: 20px;">Nonaktif</span>
                                @endif
                            </td>

                            <!-- AKSI LENGKAP TERMASUK CETAK KARTU INDIVIDU -->
                            <td style="padding: 14px 20px; vertical-align: middle; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 6px; flex-wrap: wrap;">
                                    
                                    <!-- TOMBOL CETAK KARTU INDIVIDU -->
                                    <a href="{{ route('admin.siswa.cetak-kartu', $item->id) }}" target="_blank" title="Cetak Kartu Siswa" style="padding: 6px 10px; background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        🖨️ Cetak
                                    </a>

                                    <!-- UBAH -->
                                    <a href="{{ route('admin.siswa.edit', $item) }}" style="padding: 6px 12px; background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                                        Ubah
                                    </a>

                                    <!-- HAPUS -->
                                    <form method="POST" action="{{ route('admin.siswa.destroy', $item) }}" style="margin: 0; display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
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
                            <td colspan="6" style="padding: 32px; text-align: center; color: #64748b;">
                                Tidak ada data siswa yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINASI / FOOTER -->
        @if(method_exists($siswa, 'hasPages') && $siswa->hasPages())
            <div style="padding: 16px 20px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                {{ $siswa->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL HAPUS PER KELAS -->
    <div id="modalHapusKelas" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
        <div style="background-color: #ffffff; border-radius: 16px; width: 100%; max-width: 450px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #0f172a; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="margin: 0; color: #ffffff; font-size: 16px; font-weight: 700;">Hapus Data Siswa Per Kelas</h3>
                <button type="button" onclick="document.getElementById('modalHapusKelas').style.display='none'" style="background: none; border: none; color: #94a3b8; font-size: 18px; cursor: pointer;">✕</button>
            </div>
            <form action="{{ route('admin.siswa.hapus-per-kelas') }}" method="POST" style="padding: 24px;">
                @csrf
                @method('DELETE')
                <div style="margin-bottom: 16px;">
                    <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px; border-radius: 8px; font-size: 12px; margin-bottom: 16px;">
                        ⚠️ <strong>Perhatian:</strong> Tindakan ini akan menghapus seluruh data siswa di kelas yang dipilih secara permanen!
                    </div>
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Pilih Kelas</label>
                    <select name="kelas_id" required style="width: 100%; padding: 10px 14px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 13px; color: #0f172a; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" onclick="document.getElementById('modalHapusKelas').style.display='none'" style="padding: 10px 16px; background-color: #f1f5f9; color: #64748b; font-size: 13px; font-weight: 600; border: 1px solid #cbd5e1; border-radius: 10px; cursor: pointer;">Batal</button>
                    <button type="submit" onclick="return confirm('Apakah Anda benar-benar yakin ingin menghapus seluruh siswa di kelas ini?')" style="padding: 10px 20px; background-color: #dc2626; color: #ffffff; font-size: 13px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer;">Ya, Hapus Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection