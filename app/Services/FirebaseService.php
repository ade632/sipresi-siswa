<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

/**
 * Wrapper pengiriman Web/Mobile Push Notification via Firebase Cloud
 * Messaging (FCM). Kredensial diambil dari service-account.json yang
 * dikonfigurasi di config/firebase.php (lihat FIREBASE_CREDENTIALS di .env).
 *
 * Ini menggantikan WhatsApp Gateway sesuai requirement: notifikasi ke
 * orang tua dikirim langsung melalui aplikasi/browser tanpa WhatsApp.
 */
class FirebaseService
{
    public function __construct(protected Messaging $messaging) {}

    public function kirimKeUser(User $user, string $judul, string $isi, array $data = []): void
    {
        $tokens = $user->deviceTokens()->pluck('token_fcm')->all();

        if (empty($tokens)) {
            return; // User belum pernah membuka aplikasi & mengizinkan notifikasi.
        }

        $message = CloudMessage::new()
            ->withNotification(FirebaseNotification::create($judul, $isi))
            ->withData($data);

        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            // Jangan sampai kegagalan push notification menggagalkan
            // proses utama (absensi/pelanggaran tetap tersimpan).
            Log::warning('Gagal mengirim FCM: '.$e->getMessage(), ['user_id' => $user->id]);
        }
    }
}
