<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PerangkatPiket;
use App\Services\AbsensiService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScanAbsensiController extends Controller
{
    public function __construct(protected AbsensiService $absensiService) {}

    public function index(Request $request)
    {
        return view('absensi.scan', [
            'rekapHariIni' => Absensi::hariIni()->count(),
        ]);
    }

    public function proses(Request $request)
    {
        $data = $request->validate([
            'kode_kartu' => ['required', 'string'],
            'metode' => ['required', 'in:qr,rfid'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        /** @var PerangkatPiket $perangkat set oleh middleware perangkat.piket */
        $perangkat = $request->attributes->get('perangkat_piket');

        try {
            $absensi = $this->absensiService->prosesScan(
                kodeKartu: $data['kode_kartu'],
                metode: $data['metode'],
                guruPiket: $request->user(),
                perangkat: $perangkat,
                lat: (float) $data['latitude'],
                lon: (float) $data['longitude'],
                ip: $request->ip(),
            );

            $isPulang = !empty($absensi->jam_pulang) && 
                        \Carbon\Carbon::parse($absensi->jam_pulang)->diffInMinutes(now()) <= 1;

            return response()->json([
                'sukses' => true,
                'siswa' => [
                    'nama' => $absensi->siswa->nama,
                    'nis' => $absensi->siswa->nis,
                    'kelas' => $absensi->siswa->kelas->nama,
                    'foto' => $absensi->siswa->foto ? asset('storage/'.$absensi->siswa->foto) : null,
                ],
                'status' => $isPulang ? 'Pulang' : $absensi->status,
                'jam' => $isPulang ? $absensi->jam_pulang : $absensi->jam_masuk,
                'tipe' => $isPulang ? 'pulang' : 'masuk',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'sukses' => false,
                'pesan' => collect($e->errors())->flatten()->first(),
            ], 422);
        }
    }

    public function feedHariIni()
    {
        $data = Absensi::hariIni()
            ->with('siswa.kelas')
            ->latest('updated_at')
            ->limit(20)
            ->get()
            ->map(fn ($a) => [
                'nama' => $a->siswa->nama,
                'kelas' => $a->siswa->kelas->nama,
                'status' => $a->status,
                'jam' => $a->jam_pulang ?? $a->jam_masuk,
            ]);

        return response()->json($data);
    }
}