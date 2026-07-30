<?php

namespace App\Services;

use App\Models\RadiusSekolah;

class GeofencingService
{
    /**
     * Hitung jarak antara dua koordinat memakai Haversine formula.
     * Hasil dalam meter.
     */
    public function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validasi apakah koordinat perangkat piket berada dalam radius
     * salah satu titik lokasi sekolah yang aktif.
     *
     * @return array{valid: bool, jarak: int|null, lokasi: string|null}
     */
    public function validasi(float $lat, float $lon): array
    {
        $lokasiAktif = RadiusSekolah::aktif();

        if ($lokasiAktif->isEmpty()) {
            // Jika belum ada radius yang dikonfigurasi, jangan blokir absensi
            // secara diam-diam -- lebih baik gagal eksplisit supaya Admin sadar.
            return ['valid' => false, 'jarak' => null, 'lokasi' => null];
        }

        $terdekat = null;
        $jarakTerdekat = PHP_FLOAT_MAX;

        foreach ($lokasiAktif as $lokasi) {
            $jarak = $this->hitungJarak($lat, $lon, (float) $lokasi->latitude, (float) $lokasi->longitude);

            if ($jarak < $jarakTerdekat) {
                $jarakTerdekat = $jarak;
                $terdekat = $lokasi;
            }
        }

        return [
            'valid' => $jarakTerdekat <= $terdekat->radius_meter,
            'jarak' => (int) round($jarakTerdekat),
            'lokasi' => $terdekat->nama_lokasi,
        ];
    }
}
