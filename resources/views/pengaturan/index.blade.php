@extends('layouts.app')
@section('title', 'Pengaturan Sekolah')
@section('page-title', 'Pengaturan Sekolah')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-12">
    
    <!-- BARIS PERTAMA: Profil Sekolah & Data Kepala Sekolah (Bersebelahan) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        
        <!-- 1. PROFIL SEKOLAH & GANTI LOGO -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col" x-data="{ fileName: '' }">
            <!-- Header Kartu -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 shrink-0">
                <h2 class="text-sm font-bold text-gray-900">Profil Sekolah</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Pengaturan identitas utama sekolah</p>
            </div>

            <div class="p-6 flex-1 flex flex-col bg-white">
                <form method="POST" action="{{ route('admin.pengaturan.profil-sekolah') }}" enctype="multipart/form-data" class="flex flex-col h-full">
                    @csrf
                    
                    <div class="space-y-5 flex-1">
                        <!-- Ganti Logo Sekolah -->
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <!-- Gambar Logo Diperbaiki Dimensinya -->
                            <img src="{{ $logoSekolahGlobal }}" alt="Logo saat ini" 
                                style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain;" 
                                class="rounded-lg border border-gray-200 bg-white p-1 shadow-sm shrink-0">
                            
                            <div class="w-full overflow-hidden">
                                <label class="block text-xs font-bold text-gray-900 mb-1.5">Ganti Logo Sekolah</label>
                                <label class="cursor-pointer inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition w-full">
                                    Pilih File Logo
                                    <input type="file" name="logo" accept="image/png,image/jpeg,image/webp" 
                                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" 
                                        class="hidden">
                                </label>
                                <span class="block text-[10px] text-gray-500 mt-1.5 truncate font-medium" x-text="fileName ? 'Terpilih: ' + fileName : 'Format: PNG, JPG, WEBP'"></span>
                            </div>
                        </div>

                        <!-- Input Nama Sekolah -->
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_sekolah" value="{{ $namaSekolahGlobal }}" required class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                        </div>
                        
                        <!-- Input Alamat Sekolah -->
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-1.5">Alamat Sekolah <span class="text-[10px] font-normal text-gray-500">(opsional)</span></label>
                            <input type="text" name="alamat_sekolah" value="{{ \App\Models\Pengaturan::get('alamat_sekolah') }}" placeholder="Masukkan alamat lengkap..." class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-5 mt-5 border-t border-gray-100 flex justify-end shrink-0">
                        <button type="submit" style="background-color: #2563eb; color: #ffffff;" class="hover:bg-blue-700 text-xs font-semibold px-6 py-2.5 rounded-xl shadow-sm transition cursor-pointer">
                            Simpan Profil Sekolah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. KEPALA SEKOLAH -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col"
            x-data="{ ttdName: '', capName: '' }">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 shrink-0">
                <h2 class="text-sm font-bold text-gray-900">Data Kepala Sekolah</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">
                    Digunakan otomatis pada Kartu Pelajar & Surat.
                </p>
            </div>

            <div class="p-6 flex-1 flex flex-col bg-white">
               <form method="POST" action="{{ route('admin.pengaturan.kepala-sekolah') }}" enctype="multipart/form-data" class="flex flex-col h-full">
                    @csrf

                    <div class="space-y-4 flex-1">
                        <!-- Data Kepala Sekolah (Grid 2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-900 mb-1.5">Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepsek" value="{{ \App\Models\Pengaturan::get('nama_kepsek') }}" placeholder="Nama & gelar..." class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-1.5">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepsek" value="{{ \App\Models\Pengaturan::get('nip_kepsek') }}" placeholder="Nomor NIP..." class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-900 mb-1.5">Jabatan</label>
                                <input type="text" name="jabatan_kepsek" value="{{ \App\Models\Pengaturan::get('jabatan_kepsek','Kepala Sekolah') }}" placeholder="Jabatan..." class="w-full rounded-xl border-gray-300 text-xs py-2.5 px-3.5 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white font-medium placeholder-gray-400">
                            </div>
                        </div>

                        <!-- File TTD & Cap (Grid 2 Kolom) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <!-- TTD -->
                            <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 flex flex-col justify-between">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-900 mb-1.5">Tanda Tangan (PNG)</label>
                                    <input type="file" name="tanda_tangan" accept=".png,.jpg,.jpeg,.webp" class="block w-full text-[10px] text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" @change="ttdName=$event.target.files[0]?.name">
                                    <span class="block text-[10px] text-gray-500 mt-1 truncate font-medium" x-text="ttdName ? 'Terpilih: ' + ttdName : ''"></span>
                                </div>

                                @if(\App\Models\Pengaturan::get('tanda_tangan_kepsek'))
                                    <div class="mt-2.5 pt-2 border-t border-gray-200 flex items-center gap-2">
                                        <span class="text-[10px] font-semibold text-gray-600">Aktif:</span>
                                        <img src="{{ asset('storage/'.\App\Models\Pengaturan::get('tanda_tangan_kepsek')) }}" style="max-height: 28px; object-fit: contain;" class="border rounded bg-white p-0.5 shadow-sm">
                                    </div>
                                @endif
                            </div>

                            <!-- CAP -->
                            <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 flex flex-col justify-between">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-900 mb-1.5">Cap Sekolah (PNG)</label>
                                    <input type="file" name="cap_sekolah" accept=".png,.jpg,.jpeg,.webp" class="block w-full text-[10px] text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" @change="capName=$event.target.files[0]?.name">
                                    <span class="block text-[10px] text-gray-500 mt-1 truncate font-medium" x-text="capName ? 'Terpilih: ' + capName : ''"></span>
                                </div>

                                @if(\App\Models\Pengaturan::get('cap_sekolah'))
                                    <div class="mt-2.5 pt-2 border-t border-gray-200 flex items-center gap-2">
                                        <span class="text-[10px] font-semibold text-gray-600">Aktif:</span>
                                        <img src="{{ asset('storage/'.\App\Models\Pengaturan::get('cap_sekolah')) }}" style="max-height: 28px; object-fit: contain;" class="border rounded bg-white p-0.5 shadow-sm">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit Kepala Sekolah -->
                    <div class="pt-5 mt-5 border-t border-gray-100 flex justify-end shrink-0">
                        <button type="submit" style="background-color: #2563eb; color: #ffffff;" class="hover:bg-blue-700 text-xs font-semibold px-6 py-2.5 rounded-xl shadow-sm transition cursor-pointer">
                            Simpan Data Kepala Sekolah
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- 3. HARI KERJA & JAM ABSENSI -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900">Hari Kerja & Jam Absensi</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Atur hari aktif sekolah, jam masuk, batas keterlambatan dan jam pulang.
            </p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.pengaturan.jam-absensi') }}" class="space-y-5">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-xs min-w-[640px]">
                        <thead class="bg-slate-50 text-slate-700 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 w-28 text-left font-bold">Hari</th>
                                <th class="px-4 py-3 w-24 text-left font-bold">Aktif?</th>
                                <th class="px-4 py-3 text-left font-bold">Jam Masuk</th>
                                <th class="px-4 py-3 text-left font-bold">Batas Terlambat</th>
                                <th class="px-4 py-3 text-left font-bold">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($jamAbsensi as $j)
                                <tr x-data="{ aktif: {{ $j->is_aktif ? 'true' : 'false' }} }" :class="!aktif && 'opacity-40'">
                                    <td class="px-4 py-3 font-bold capitalize whitespace-nowrap text-gray-900">{{ $j->hari }}</td>
                                    <td class="px-4 py-3">
                                        <!-- Sakelar Toggle -->
                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input type="hidden" name="hari[{{ $j->hari }}][is_aktif]" value="0">
                                            <input type="checkbox" name="hari[{{ $j->hari }}][is_aktif]" value="1" x-model="aktif"
                                                {{ $j->is_aktif ? 'checked' : '' }} class="hidden">
                                            <div class="w-11 h-6 rounded-full transition-all duration-200 flex items-center px-0.5"
                                                 :style="aktif ? 'background-color: #059669;' : 'background-color: #cbd5e1;'">
                                                <div class="w-5 h-5 rounded-full transition-all duration-200"
                                                     :style="aktif ? 'background-color: #ffffff; transform: translateX(20px);' : 'background-color: #ffffff; transform: translateX(0px);'"></div>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="hari[{{ $j->hari }}][jam_masuk]" value="{{ substr($j->jam_masuk,0,5) }}" :readonly="!aktif"
    class="w-full rounded-xl border-gray-300 text-xs shadow-sm focus:ring-blue-500 focus:border-blue-500 readonly:bg-gray-100 readonly:text-gray-400 py-2 px-3 text-gray-900 bg-white font-medium">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="hari[{{ $j->hari }}][jam_masuk_terlambat]" value="{{ substr($j->jam_masuk_terlambat,0,5) }}" :readonly="!aktif"
                                            class="w-full rounded-xl border-gray-300 text-xs shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 py-2 px-3 text-gray-900 bg-white font-medium">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="time" name="hari[{{ $j->hari }}][jam_pulang]" value="{{ substr($j->jam_pulang,0,5) }}" :readonly="!aktif"
                                            class="w-full rounded-xl border-gray-300 text-xs shadow-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-400 py-2 px-3 text-gray-900 bg-white font-medium">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" style="background-color: #059669; color: #ffffff;" class="hover:bg-emerald-700 text-xs font-semibold px-6 py-2.5 rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Jam Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TITIK LOKASI & RADIUS SEKOLAH -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900">Titik Lokasi & Radius Sekolah</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Atur lokasi sekolah yang digunakan untuk validasi absensi siswa.
            </p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                <!-- Kiri: Daftar Lokasi Aktif -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Daftar Titik Lokasi Aktif</h3>
                    @forelse ($radius as $r)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex justify-between items-center hover:shadow-md transition">
                            <div class="overflow-hidden pr-3">
                                <p class="font-bold text-slate-900 text-xs">{{ $r->nama_lokasi }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">Lat/Long: {{ $r->latitude }}, {{ $r->longitude }} · <span class="text-blue-600 font-bold">Radius: {{ $r->radius_meter }}m</span></p>
                            </div>
                            <form action="{{ route('admin.pengaturan.radius.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus titik lokasi ini?');" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: #fef2f2; color: #dc2626;" class="hover:bg-red-100 text-xs font-semibold rounded-xl px-4 py-2 border border-red-200 transition shadow-sm cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                            <p class="text-xs text-gray-500 font-medium">Belum ada titik lokasi diatur.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Kanan: Form Tambah Lokasi Baru -->
                <div>
                    <form method="POST" action="{{ route('admin.pengaturan.radius') }}" class="space-y-4 bg-gray-50/70 border border-gray-200 rounded-2xl p-5 shadow-sm">
                        @csrf
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-800 pb-1 border-b border-gray-200">Tambah Titik Lokasi Baru</h3>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lokasi</label>
                            <input type="text" name="nama_lokasi" placeholder="Contoh: Gerbang Utama" required class="w-full rounded-xl border-gray-300 text-xs bg-white py-2.5 px-3.5 text-gray-900 font-medium placeholder-gray-400 shadow-sm focus:ring-rose-500 focus:border-rose-500">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Latitude</label>
                                <input type="number" step="0.0000001" name="latitude" placeholder="-3.xxxx" required class="w-full rounded-xl border-gray-300 text-xs bg-white py-2.5 px-3.5 text-gray-900 font-medium placeholder-gray-400 shadow-sm focus:ring-rose-500 focus:border-rose-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Longitude</label>
                                <input type="number" step="0.0000001" name="longitude" placeholder="102.xxxx" required class="w-full rounded-xl border-gray-300 text-xs bg-white py-2.5 px-3.5 text-gray-900 font-medium placeholder-gray-400 shadow-sm focus:ring-rose-500 focus:border-rose-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Radius (Meter)</label>
                            <input type="number" name="radius_meter" placeholder="100" value="100" required class="w-full rounded-xl border-gray-300 text-xs bg-white py-2.5 px-3.5 text-gray-900 font-medium placeholder-gray-400 shadow-sm focus:ring-rose-500 focus:border-rose-500">
                        </div>

                        <p class="text-[11px] text-gray-500 font-medium pt-1">Tips: Buka Google Maps, klik kanan titik lokasi untuk menyalin koordinat.</p>
                        
                        <div class="pt-2">
                            <button type="submit" style="background-color: #e11d48; color: #ffffff;" class="w-full hover:bg-rose-700 text-xs font-semibold rounded-xl py-2.5 shadow-sm transition cursor-pointer">
                                Tambah Titik Lokasi
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection