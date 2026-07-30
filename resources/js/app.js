import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Registrasi Service Worker untuk PWA (installable, offline shell).
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((err) => {
            console.warn('Service worker gagal didaftarkan:', err);
        });
    });
}

/**
 * Helper global: ambil CSRF token dari meta tag untuk dipakai fetch() manual.
 */
window.csrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
