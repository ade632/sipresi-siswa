import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

/**
 * Inisialisasi Firebase Cloud Messaging untuk Web Push Notification.
 * Dipanggil dari halaman Portal Orang Tua & Dashboard Kepsek (yang butuh
 * notifikasi realtime). Kredensial diambil dari VITE_FIREBASE_* di .env
 * (lihat .env.example).
 *
 * Ini menggantikan WhatsApp Gateway: notifikasi kehadiran/pelanggaran/dsb
 * dikirim langsung ke browser/HP orang tua via FCM.
 */
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
};

export async function aktifkanNotifikasi() {
    if (!('Notification' in window)) {
        alert('Browser ini tidak mendukung notifikasi push.');
        return;
    }

    const izin = await Notification.requestPermission();
    if (izin !== 'granted') return;

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    const token = await getToken(messaging, { vapidKey: import.meta.env.VITE_FIREBASE_VAPID_KEY });

    if (!token) return;

    await fetch('/api/device-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken(),
        },
        body: JSON.stringify({ token_fcm: token, platform: 'web' }),
    });

    onMessage(messaging, (payload) => {
        new Notification(payload.notification.title, {
            body: payload.notification.body,
            icon: '/icons/icon-192.png',
        });
    });
}

window.aktifkanNotifikasi = aktifkanNotifikasi;
