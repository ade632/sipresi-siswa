<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\JamAbsensiSetting;
use App\Models\KalenderAkademik;
use App\Models\Pengaturan;
use App\Models\RadiusSekolah;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $namaHariUrut = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        $jamAbsensiAda = JamAbsensiSetting::all()->keyBy('hari');

        $jamAbsensi = collect($namaHariUrut)->map(function ($hari) use ($jamAbsensiAda) {
            return $jamAbsensiAda->get($hari) ?? new JamAbsensiSetting([
                'hari' => $hari, 'jam_masuk' => '07:00', 'jam_masuk_terlambat' => '07:15',
                'jam_pulang' => '15:30', 'is_aktif' => false,
            ]);
        });

        return view('pengaturan.index', [
            'radius' => RadiusSekolah::all(),
            'jamAbsensi' => $jamAbsensi,
        ]);
    }

    public function simpanRadius(Request $request)
    {
        $data = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meter' => ['required', 'integer', 'min:10', 'max:2000'],
        ]);

        RadiusSekolah::create([...$data, 'is_aktif' => true]);

        AuditLog::catat("Menambah titik radius sekolah: {$data['nama_lokasi']}", 'pengaturan');

        return back()->with('success', 'Titik lokasi & radius berhasil disimpan.');
    }

    public function destroyRadius($id)
    {
        $radius = RadiusSekolah::findOrFail($id);
        $namaLokasi = $radius->nama_lokasi;
        
        $radius->delete();

        AuditLog::catat("Menghapus titik radius sekolah: {$namaLokasi}", 'pengaturan');

        return back()->with('success', 'Titik lokasi & radius berhasil dihapus.');
    }

    public function simpanJamAbsensi(Request $request)
    {
        $data = $request->validate([
            'hari' => ['required', 'array'],
            'hari.*.is_aktif' => ['nullable'],
            'hari.*.jam_masuk' => ['required', 'date_format:H:i'],
            'hari.*.jam_masuk_terlambat' => ['required', 'date_format:H:i'],
            'hari.*.jam_pulang' => ['required', 'date_format:H:i'],
        ]);

        foreach ($data['hari'] as $namaHari => $item) {
            JamAbsensiSetting::updateOrCreate(['hari' => $namaHari], [
                'jam_masuk' => $item['jam_masuk'],
                'jam_masuk_terlambat' => $item['jam_masuk_terlambat'],
                'jam_pulang' => $item['jam_pulang'],
                'is_aktif' => isset($item['is_aktif']) && $item['is_aktif'] == '1',
            ]);
        }

        AuditLog::catat('Memperbarui pengaturan hari kerja & jam absensi', 'pengaturan');

        return back()->with('success', 'Pengaturan hari kerja & jam absensi berhasil disimpan.');
    }

    public function simpanProfilSekolah(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'alamat_sekolah' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        Pengaturan::set('nama_sekolah', $data['nama_sekolah']);
        Pengaturan::set('alamat_sekolah', $data['alamat_sekolah'] ?? null);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('sekolah', 'public');
            Pengaturan::set('logo_sekolah_path', $path);
        }

        AuditLog::catat('Memperbarui profil sekolah (nama/logo)', 'pengaturan');

        return back()->with('success', 'Profil sekolah berhasil disimpan.');
    }

    public function simpanKepalaSekolah(Request $request)
    {
        $data = $request->validate([
            'nama_kepsek'  => ['nullable', 'string', 'max:150'],
            'nip_kepsek'   => ['nullable', 'string', 'max:50'],
            'tanda_tangan' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'cap_sekolah'  => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        if (isset($data['nama_kepsek'])) {
            Pengaturan::set('nama_kepsek', $data['nama_kepsek']);
        }
        if (isset($data['nip_kepsek'])) {
            Pengaturan::set('nip_kepsek', $data['nip_kepsek']);
        }

        if ($request->hasFile('tanda_tangan')) {
            $pathTtd = $request->file('tanda_tangan')->store('pengaturan', 'public');
            Pengaturan::set('tanda_tangan_kepsek', $pathTtd);
        }

        if ($request->hasFile('cap_sekolah')) {
            $pathCap = $request->file('cap_sekolah')->store('pengaturan', 'public');
            Pengaturan::set('cap_sekolah', $pathCap);
        }

        AuditLog::catat('Memperbarui data kepala sekolah & tanda tangan kartu', 'pengaturan');

        return back()->with('success', 'Pengaturan kepala sekolah & tanda tangan berhasil disimpan.');
    }

    public function kalenderAkademik()
    {
        return view('pengaturan.kalender-akademik', [
            'kalender' => KalenderAkademik::with('tahunAjaran')->orderByDesc('tanggal')->paginate(20),
            'tahunAjaran' => TahunAjaran::orderByDesc('id')->get(),
        ]);
    }

    public function simpanKalender(Request $request)
    {
        $data = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:libur_nasional,libur_sekolah,kegiatan_sekolah'],
            'keterangan' => ['required', 'string', 'max:150'],
        ]);

        KalenderAkademik::create($data);
        AuditLog::catat("Menambah kalender akademik: {$data['keterangan']}", 'kalender_akademik');

        return back()->with('success', 'Kalender akademik berhasil ditambahkan.');
    }
}