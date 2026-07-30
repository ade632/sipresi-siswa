importScripts('https://www.gstatic.com/firebasejs/10.14.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.14.0/firebase-messaging-compat.js');

// GANTI nilai di bawah ini sesuai kredensial Firebase project Anda
// (sama dengan VITE_FIREBASE_* di file .env). Service worker tidak bisa
// membaca environment variable Laravel, jadi harus di-hardcode di sini.
firebase.initializeApp({
    apiKey: 'GANTI_DENGAN_API_KEY',
    authDomain: 'GANTI_DENGAN_AUTH_DOMAIN',
    projectId: 'GANTI_DENGAN_PROJECT_ID',
    messagingSenderId: 'GANTI_DENGAN_SENDER_ID',
    appId: 'GANTI_DENGAN_APP_ID',
});

const messaging = firebase.messaging();

// Menampilkan notifikasi saat aplikasi/tab sedang tidak aktif (background).
messaging.onBackgroundMessage((payload) => {
    self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        icon: '/icons/icon-192.png',
        badge: '/icons/icon-192.png',
    });
});
