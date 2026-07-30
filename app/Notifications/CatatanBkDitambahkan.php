<?php

namespace App\Notifications;

use App\Models\CatatanBk;
use App\Notifications\Channels\NotifikasiChannel;
use Illuminate\Notifications\Notification;

class CatatanBkDitambahkan extends Notification
{
    public function __construct(protected CatatanBk $catatan) {}

    public function via(object $notifiable): array
    {
        return [NotifikasiChannel::class];
    }

    public function toNotifikasi(object $notifiable): array
    {
        $siswa = $this->catatan->siswa;

        $jenisLabel = match ($this->catatan->jenis) {
            'konseling' => 'sesi konseling',
            'pembinaan' => 'pembinaan',
            'panggilan_ortu' => 'panggilan orang tua',
            'tindak_lanjut' => 'tindak lanjut BK',
            default => 'catatan BK',
        };

        return [
            'siswa_id' => $siswa->id,
            'judul' => 'Informasi dari Guru BK',
            'pesan' => "Anak Anda {$siswa->nama} mendapat catatan {$jenisLabel} dari Guru BK. Silakan cek Portal Orang Tua untuk detail.",
            'tipe' => 'bk',
            'data' => [
                'catatan_bk_id' => (string) $this->catatan->id,
                'jenis' => 'bk',
            ],
        ];
    }
}
