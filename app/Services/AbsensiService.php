<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\JamAbsensiSetting;
use App\Models\KalenderAkademik;
use App\Models\KartuAkses;
use App\Models\PerangkatPiket;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\AbsensiTercatat;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * Mengorkestrasi alur absensi sesuai revisi bisnis:
 * Siswa hanya membawa kartu (QR/RFID). Guru Piket yang melakukan scan
 * dari device piket yang sudah terdaftar (whitelist perangkat_piket).
 * Validasi lokasi & jam dilakukan terhadap device Guru Piket, bukan
 * device siswa, sehingga jauh lebih tahan manipulasi GPS.
 */
class AbsensiService
{
    public function __construct(
        protected GeofencingService $geofencing,
        protected SkorKedisiplinanService $skorService,
    ) {}

    /**
     * Proses scan QR atau RFID oleh Guru Piket.
     *
     * @param  string  $kodeKartu  Token QR (uuid) atau UID RFID (hex)
     * @param  'qr'|'rfid'  $metode
     */
    public function prosesScan(
        string $kodeKartu,
        string $metode,
        User $guruPiket,
        PerangkatPiket $perangkat,
        float $lat,
        float $lon,
        string $ip,
    ): Absensi {
        // 1. Validasi geofencing device piket.
        $hasilGeofence = $this->geofencing->validasi($lat, $lon);

        if (! $hasilGeofence['valid']) {
            throw ValidationException::withMessages([
                'lokasi' => sprintf(
                    'Perangkat piket berada %s meter dari lokasi sekolah (di luar radius yang diizinkan).',
                    $hasilGeofence['jarak'] ?? 'tidak diketahui'
                ),
            ]);
        }

        // 2. Cari kartu & siswa.
        $kartu = KartuAkses::where('kode', $kodeKartu)
            ->where('tipe', $metode)
            ->where('is_aktif', true)
            ->with('siswa')
            ->first();

        if (! $kartu || ! $kartu->siswa) {
            throw ValidationException::withMessages([
                'kartu' => 'Kartu tidak dikenali atau sudah tidak aktif.',
            ]);
        }

        $siswa = $kartu->siswa;

        if ($siswa->status !== 'aktif') {
            throw ValidationException::withMessages([
                'siswa' => "Siswa {$siswa->nama} berstatus {$siswa->status}, tidak dapat melakukan absensi.",
            ]);
        }

        // 3. Cek hari libur (kalender akademik) DAN hari non-sekolah rutin.
        $hariIni = now()->toDateString();
        if (KalenderAkademik::isLibur($hariIni)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Hari ini terdaftar sebagai hari libur pada kalender akademik.',
            ]);
        }

        $jamSetting = JamAbsensiSetting::hariIni();

        if (! $jamSetting || ! $jamSetting->is_aktif) {
            throw ValidationException::withMessages([
                'tanggal' => 'Hari ini bukan hari sekolah (libur rutin), absensi tidak dapat dilakukan.',
            ]);
        }

        // 4. Cek duplikat absensi masuk hari ini.
        $existing = Absensi::where('siswa_id', $siswa->id)->whereDate('tanggal', $hariIni)->first();

        if (! $existing) {
            return $this->catatAbsenMasuk($siswa, $metode, $guruPiket, $perangkat, $lat, $lon, $hasilGeofence['jarak'], $ip, $jamSetting);
        }

        // Jika sudah ada record & belum ada jam pulang -> anggap ini scan pulang.
        if ($existing->jam_masuk && ! $existing->jam_pulang) {
            return $this->catatAbsenPulang($existing, $jamSetting);
        }

        // Sudah absen masuk DAN pulang hari ini -> tolak tegas.
        if ($existing->jam_masuk && $existing->jam_pulang) {
            throw ValidationException::withMessages([
                'duplikat' => "{$siswa->nama} sudah tercatat Masuk ({$existing->jam_masuk}) dan Pulang ({$existing->jam_pulang}) hari ini. Tidak dapat absen lagi.",
            ]);
        }

        // Record ada tapi bukan dari scan.
        throw ValidationException::withMessages([
            'duplikat' => "{$siswa->nama} sudah tercatat berstatus ".ucfirst($existing->status)." hari ini. Hubungi Guru Piket jika status ini keliru dan perlu diubah.",
        ]);
    }

    protected function catatAbsenMasuk(
        Siswa $siswa,
        string $metode,
        User $guruPiket,
        PerangkatPiket $perangkat,
        float $lat,
        float $lon,
        int $jarak,
        string $ip,
        ?JamAbsensiSetting $jamSetting,
    ): Absensi {
        $jamSekarang = now();
        $status = 'hadir';

        if ($jamSetting && $jamSetting->jam_masuk_terlambat) {
            $batasTerlambat = Carbon::parse($jamSetting->jam_masuk_terlambat);
            if ($jamSekarang->format('H:i:s') > $batasTerlambat->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        $absensi = Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $jamSekarang->toDateString(),
            'jam_masuk' => $jamSekarang->format('H:i:s'),
            'status' => $status,
            'metode' => $metode,
            'perangkat_piket_id' => $perangkat->id,
            'latitude' => $lat,
            'longitude' => $lon,
            'jarak_meter' => $jarak,
            'ip_address' => $ip,
            'dicatat_oleh' => $guruPiket->id,
        ]);

        $perangkat->update(['terakhir_dipakai' => $jamSekarang]);

        $siswa->orangTua?->notify(new AbsensiTercatat($absensi));

        return $absensi;
    }

    protected function catatAbsenPulang(Absensi $absensi, ?JamAbsensiSetting $jamSetting): Absensi
    {
        $jamSekarang = now();
        
        // Validasi jarak waktu minimal 60 menit dari jam masuk
        $waktuMasuk = Carbon::parse($absensi->tanggal . ' ' . $absensi->jam_masuk);
        $selisihMenit = $waktuMasuk->diffInMinutes($jamSekarang, false);

        if ($selisihMenit < 60) {
            $sisaMenit = ceil(60 - $selisihMenit);
            throw ValidationException::withMessages([
                'waktu_pulang' => ["Belum bisa absen pulang! Jarak waktu minimal 60 menit dari absen masuk ({$absensi->jam_masuk}). Kurang sekitar {$sisaMenit} menit lagi."],
            ]);
        }

        $absensi->update(['jam_pulang' => $jamSekarang->format('H:i:s')]);

        $absensi->siswa->orangTua?->notify(new AbsensiTercatat($absensi, pulang: true));

        return $absensi->fresh();
    }

    /**
     * Input absensi manual (izin/sakit/dispensasi/alpa) oleh Guru Piket.
     */
    public function catatManual(Siswa $siswa, string $status, User $guruPiket, ?string $keterangan = null, ?string $lampiran = null): Absensi
    {
        $absensi = Absensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'tanggal' => now()->toDateString()],
            [
                'status' => $status,
                'metode' => 'manual',
                'dicatat_oleh' => $guruPiket->id,
                'keterangan' => $keterangan,
                'lampiran' => $lampiran,
            ]
        );

        $absensi->siswa->orangTua?->notify(new AbsensiTercatat($absensi));

        return $absensi;
    }
}