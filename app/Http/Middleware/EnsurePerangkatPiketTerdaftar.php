<?php

namespace App\Http\Middleware;

use App\Models\PerangkatPiket;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mencatat device yang dipakai Guru Piket untuk scan absensi. Fingerprint
 * dikirim dari JS (resources/js/scan-qr.js) via header X-Device-Fingerprint.
 *
 * Device BARU otomatis terdaftar saat pertama kali dipakai scan -- tidak
 * perlu didaftarkan manual oleh Admin dulu, supaya Guru Piket bisa langsung
 * pakai kamera tanpa hambatan. Admin tetap bisa melihat & menonaktifkan
 * device tertentu di menu Perangkat Piket kalau ada device mencurigakan
 * (misalnya device yang tidak seharusnya dipakai untuk piket).
 *
 * Keamanan lokasi tetap dijaga oleh validasi geofencing (radius sekolah)
 * di AbsensiService -- whitelist device ini murni untuk audit trail
 * "device mana saja yang pernah dipakai absen", bukan gerbang keamanan utama.
 */
class EnsurePerangkatPiketTerdaftar
{
    public function handle(Request $request, Closure $next): Response
    {
        $fingerprint = $request->header('X-Device-Fingerprint');

        abort_if(! $fingerprint, 422, 'Perangkat tidak dikenali. Muat ulang halaman scan.');

        $perangkat = PerangkatPiket::where('device_fingerprint', $fingerprint)->first();

        if ($perangkat && ! $perangkat->is_aktif) {
            abort(403, 'Perangkat ini telah dinonaktifkan oleh Administrator. Hubungi Administrator jika ini keliru.');
        }

        if (! $perangkat) {
            $perangkat = PerangkatPiket::create([
                'nama_device' => 'Device Piket (otomatis) #'.(PerangkatPiket::count() + 1),
                'device_fingerprint' => $fingerprint,
                'is_aktif' => true,
                'didaftarkan_oleh' => $request->user()->id,
            ]);
        }

        // Simpan ke request supaya bisa dipakai langsung di controller/service.
        $request->attributes->set('perangkat_piket', $perangkat);

        return $next($request);
    }
}
