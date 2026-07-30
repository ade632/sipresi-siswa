<?php

namespace App\Notifications;

use App\Models\Pelanggaran;
use App\Notifications\Channels\NotifikasiChannel;
use Illuminate\Notifications\Notification;

class PelanggaranTercatat extends Notification
{
    public function __construct(protected Pelanggaran $pelanggaran) {}

    public function via(object $notifiable): array
    {
        return [NotifikasiChannel::class];
    }

    public function toNotifikasi(object $notifiable): array
    {
        $siswa = $this->pelanggaran->siswa;
        $jenis = $this->pelanggaran->jenisPelanggaran;
        $totalPoin = $siswa->totalPoinPelanggaran();

        $pesan = "Anak Anda {$siswa->nama} tercatat melakukan pelanggaran: {$jenis->nama} ({$jenis->poin} poin).\n\n"
            ."Total poin pelanggaran saat ini: {$totalPoin} poin.";

        return [
            'siswa_id' => $siswa->id,
            'judul' => 'Informasi Pelanggaran',
            'pesan' => $pesan,
            'tipe' => 'pelanggaran',
            'data' => [
                'pelanggaran_id' => (string) $this->pelanggaran->id,
                'jenis' => 'pelanggaran',
            ],
        ];
    }
}
