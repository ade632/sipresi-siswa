<?php

namespace Database\Seeders;

use App\Models\JamAbsensiSetting;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\RadiusSekolah;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::updateOrCreate(
            ['nama' => '2025/2026', 'semester' => 'ganjil'],
            [
                'tanggal_mulai' => '2025-07-14',
                'tanggal_selesai' => '2025-12-20',
                'is_aktif' => true,
            ]
        );

        $jurusanList = [
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'TKR', 'nama' => 'Teknik Kendaraan Ringan'],
            ['kode' => 'DPIB', 'nama' => 'Desain Pemodelan dan Informasi Bangunan'],
            ['kode' => 'AKL', 'nama' => 'Akuntansi dan Keuangan Lembaga'],
        ];

        foreach ($jurusanList as $j) {
            $jurusan = Jurusan::updateOrCreate(['kode' => $j['kode']], $j);

            foreach ([10, 11, 12] as $tingkat) {
                $romawi = $tingkat === 10 ? 'X' : ($tingkat === 11 ? 'XI' : 'XII');
                Kelas::updateOrCreate(
                    ['nama' => "{$romawi} {$j['kode']} 1", 'tahun_ajaran_id' => $tahunAjaran->id],
                    ['tingkat' => $tingkat, 'jurusan_id' => $jurusan->id, 'tahun_ajaran_id' => $tahunAjaran->id]
                );
            }
        }

        // Jam absensi standar Senin-Jumat. Sabtu & Minggu libur (5 hari sekolah).
        // Kalau sekolah Anda tetap masuk hari Sabtu, ubah lewat menu
        // Pengaturan > Radius & Jam Absensi > pilih "Sabtu" > centang "Hari aktif".
        $jamKerja = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        foreach ($jamKerja as $hari) {
            JamAbsensiSetting::updateOrCreate(['hari' => $hari], [
                'jam_masuk' => '07:00', 'jam_masuk_terlambat' => '07:15', 'jam_pulang' => '15:30', 'is_aktif' => true,
            ]);
        }
        JamAbsensiSetting::updateOrCreate(['hari' => 'sabtu'], [
            'jam_masuk' => '00:00', 'jam_masuk_terlambat' => '00:00', 'jam_pulang' => '00:00', 'is_aktif' => false,
        ]);
        JamAbsensiSetting::updateOrCreate(['hari' => 'minggu'], [
            'jam_masuk' => '00:00', 'jam_masuk_terlambat' => '00:00', 'jam_pulang' => '00:00', 'is_aktif' => false,
        ]);

        RadiusSekolah::updateOrCreate(['nama_lokasi' => 'Gerbang Utama SMKN 1 Rejang Lebong'], [
            'latitude' => -3.4700, 'longitude' => 102.6800, 'radius_meter' => 100, 'is_aktif' => true,
        ]);
    }
}
