<?php

namespace App\Notifications\Channels;

use App\Models\Notifikasi;
use App\Services\FirebaseService;
use Illuminate\Notifications\Notification;

/**
 * Channel gabungan: menyimpan riwayat notifikasi ke tabel `notifikasi`
 * (dipakai untuk lonceng notifikasi di Portal Ortu) sekaligus mengirim
 * push notification via FCM ke device yang terdaftar.
 */
class NotifikasiChannel
{
    public function __construct(protected FirebaseService $firebase) {}

    public function send(object $notifiable, Notification $notification): void
    {
        $payload = $notification->toNotifikasi($notifiable);

        Notifikasi::create([
            'user_id' => $notifiable->id,
            'siswa_id' => $payload['siswa_id'] ?? null,
            'judul' => $payload['judul'],
            'pesan' => $payload['pesan'],
            'tipe' => $payload['tipe'],
        ]);

        $this->firebase->kirimKeUser($notifiable, $payload['judul'], $payload['pesan'], $payload['data'] ?? []);
    }
}
