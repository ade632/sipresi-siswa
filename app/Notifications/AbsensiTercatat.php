<?php

namespace App\Notifications;

use App\Models\Absensi;
use App\Notifications\Channels\NotifikasiChannel;
use Illuminate\Notifications\Notification;

class AbsensiTercatat extends Notification
{
    public function __construct(protected Absensi $absensi, protected bool $pulang = false) {}

    public function via(object $notifiable): array
    {
        return [NotifikasiChannel::class];
    }

    public function toNotifikasi(object $notifiable): array
    {
        $siswa = $this->absensi->siswa;
        $jam = $this->pulang ? $this->absensi->jam_pulang : $this->absensi->jam_masuk;
        $jamFormat = \Carbon\Carbon::parse($jam)->format('H.i').' WIB';

        if ($this->pulang) {
            $judul = 'Informasi Kepulangan';
            $pesan = "Anak Anda {$siswa->nama} telah pulang sekolah pada pukul {$jamFormat}.";
            $tipe = 'pulang';
        } else {
            $judul = 'Informasi Kehadiran';
            $statusLabel = match ($this->absensi->status) {
                'hadir' => 'Hadir tepat waktu.',
                'terlambat' => 'Terlambat.',
                'izin' => 'Izin.',
                'sakit' => 'Sakit.',
                'dispensasi' => 'Dispensasi.',
                'alpa' => 'Tidak hadir tanpa keterangan (Alpa).',
                default => '-',
            };
            $pesan = "Anak Anda {$siswa->nama} telah melakukan absensi pada pukul {$jamFormat}.\n\nStatus: {$statusLabel}";
            $tipe = $this->absensi->status === 'alpa' ? 'alpa' : ($this->absensi->status === 'terlambat' ? 'terlambat' : 'kehadiran');
        }

        return [
            'siswa_id' => $siswa->id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'data' => [
                'absensi_id' => (string) $this->absensi->id,
                'jenis' => 'absensi',
            ],
        ];
    }
}
