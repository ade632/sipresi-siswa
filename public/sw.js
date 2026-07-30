const CACHE_NAME = 'sipresi-siswa-v2';

// Hanya asset statis yang di-cache untuk app shell. Halaman data (dashboard,
// absensi, dsb) SENGAJA tidak di-cache karena datanya harus selalu realtime
// (absensi & poin pelanggaran tidak boleh menampilkan data basi).
const ASSET_SHELL = [
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSET_SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Hanya tangani GET untuk asset statis (icons, manifest, build assets).
    // Request API/halaman dinamis selalu lewat network langsung.
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    const isStaticAsset = url.pathname.startsWith('/icons/')
        || url.pathname.startsWith('/build/')
        || url.pathname === '/manifest.json';

    if (!isStaticAsset) return;

    event.respondWith(
        caches.match(request).then((cached) => cached || fetch(request))
    );
});
