import { Html5Qrcode } from 'html5-qrcode';
import { getDeviceFingerprint } from './device-fingerprint';

/**
 * Alpine component untuk halaman kios Scan Absensi Guru Piket.
 * Menangani 2 metode input: kamera QR (html5-qrcode) dan RFID reader
 * mode keyboard-emulation (HID) yang mengetik UID ke input text lalu Enter.
 *
 * Lokasi (lat/long) diambil dari device Guru Piket ini via Geolocation API,
 * dikirim bersama setiap request scan untuk divalidasi geofencing di server.
 */
window.scanAbsensi = function () {
    return {
        kameraAktif: false,
        kameraStatus: 'memuat', // memuat | aktif | error | tidak-ada-izin | tidak-ada-kamera
        kameraPesan: 'Menyiapkan kamera...',
        rfidBuffer: '',
        hasilTerakhir: null,
        feed: [],
        posisi: { lat: null, lon: null },
        sedangMemproses: false,
        html5Qr: null,

        async init() {
            this.ambilLokasi();
            await this.muatFeed();
            setInterval(() => this.muatFeed(), 10000);

            // Delay singkat supaya elemen #qr-reader sudah punya ukuran/layout final
            // sebelum html5-qrcode mengukur dimensinya (mencegah video 0x0 / layar hitam).
            this.$nextTick(() => setTimeout(() => this.mulaiKamera(), 200));

            // Jaga fokus tetap di input RFID supaya reader (mode keyboard) selalu terbaca,
            // kecuali user sedang mengetik di field lain.
            setInterval(() => {
                if (document.activeElement.tagName !== 'INPUT' || document.activeElement === this.$refs.rfidInput) {
                    this.$refs.rfidInput?.focus();
                }
            }, 3000);
        },

        ambilLokasi() {
            if (!navigator.geolocation) {
                alert('Browser ini tidak mendukung Geolocation API. Gunakan Chrome/Safari versi terbaru.');
                return;
            }

            navigator.geolocation.watchPosition(
                (pos) => {
                    this.posisi.lat = pos.coords.latitude;
                    this.posisi.lon = pos.coords.longitude;
                },
                (err) => console.warn('Gagal mengambil lokasi:', err.message),
                { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 }
            );
        },

        async mulaiKamera() {
            this.kameraStatus = 'memuat';
            this.kameraPesan = 'Menyiapkan kamera...';

            if (!window.isSecureContext) {
                this.kameraStatus = 'error';
                this.kameraPesan = 'Kamera hanya bisa diakses lewat HTTPS atau localhost. Buka halaman ini via http://localhost atau pasang SSL di server.';
                return;
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.kameraStatus = 'error';
                this.kameraPesan = 'Browser ini tidak mendukung akses kamera. Gunakan Chrome/Edge/Safari versi terbaru.';
                return;
            }

            // Bersihkan instance lama kalau ini percobaan ulang (retry).
            if (this.html5Qr) {
                try { await this.html5Qr.stop(); } catch (e) {}
                try { this.html5Qr.clear(); } catch (e) {}
            }

            this.html5Qr = new Html5Qrcode('qr-reader');

            const config = {
                fps: 10,
                qrbox: (viewfinderWidth, viewfinderHeight) => {
                    const size = Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.7);
                    return { width: Math.max(size, 180), height: Math.max(size, 180) };
                },
            };

            const onScan = (decodedText) => this.prosesScan(decodedText, 'qr');
            const onFail = () => {}; // dipanggil terus tiap frame yang tidak terbaca -- normal, diabaikan.

            try {
                // Coba kamera belakang dulu (facingMode environment), cocok untuk HP/tablet.
                await this.html5Qr.start({ facingMode: 'environment' }, config, onScan, onFail);
                this.kameraStatus = 'aktif';
                this.kameraAktif = true;
                return;
            } catch (errBelakang) {
                console.warn('Kamera belakang gagal, mencoba kamera lain:', errBelakang);
            }

            try {
                // Fallback: banyak laptop/PC cuma punya 1 kamera depan (webcam).
                const daftarKamera = await Html5Qrcode.getCameras();

                if (!daftarKamera || daftarKamera.length === 0) {
                    this.kameraStatus = 'tidak-ada-kamera';
                    this.kameraPesan = 'Tidak ada kamera terdeteksi di perangkat ini. Gunakan input RFID, atau sambungkan webcam lalu klik "Coba Lagi".';
                    return;
                }

                await this.html5Qr.start(daftarKamera[0].id, config, onScan, onFail);
                this.kameraStatus = 'aktif';
                this.kameraAktif = true;
            } catch (err) {
                console.error('Gagal memulai kamera:', err);
                this.kameraAktif = false;

                const namaError = err?.name || String(err);
                if (namaError.includes('NotAllowedError') || namaError.includes('Permission')) {
                    this.kameraStatus = 'tidak-ada-izin';
                    this.kameraPesan = 'Akses kamera ditolak. Klik ikon 🔒/kamera di address bar browser, izinkan akses kamera untuk situs ini, lalu klik "Coba Lagi".';
                } else if (namaError.includes('NotFoundError')) {
                    this.kameraStatus = 'tidak-ada-kamera';
                    this.kameraPesan = 'Tidak ada kamera terdeteksi di perangkat ini. Gunakan input RFID sebagai alternatif.';
                } else if (namaError.includes('NotReadableError')) {
                    this.kameraStatus = 'error';
                    this.kameraPesan = 'Kamera sedang dipakai aplikasi lain (Zoom/Teams/aplikasi kamera lain). Tutup aplikasi tersebut lalu klik "Coba Lagi".';
                } else {
                    this.kameraStatus = 'error';
                    this.kameraPesan = 'Kamera gagal diaktifkan (' + namaError + '). Klik "Coba Lagi", atau gunakan input RFID.';
                }
            }
        },

        prosesRfid() {
            if (!this.rfidBuffer.trim()) return;
            this.prosesScan(this.rfidBuffer.trim(), 'rfid');
            this.rfidBuffer = '';
        },

        async prosesScan(kode, metode) {
            if (this.sedangMemproses) return;
            if (!this.posisi.lat || !this.posisi.lon) {
                this.hasilTerakhir = { sukses: false, pesan: 'Menunggu lokasi GPS perangkat, coba lagi sebentar.' };
                return;
            }

            this.sedangMemproses = true;

            try {
                const fingerprint = await getDeviceFingerprint();

                const res = await fetch('/piket/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken(),
                        'X-Device-Fingerprint': fingerprint,
                    },
                    body: JSON.stringify({
                        kode_kartu: kode,
                        metode,
                        latitude: this.posisi.lat,
                        longitude: this.posisi.lon,
                    }),
                });

                const data = await res.json();
                this.hasilTerakhir = data;

                if (data.sukses) {
                    this.beep();
                    await this.muatFeed();
                }
            } catch (e) {
                this.hasilTerakhir = { sukses: false, pesan: 'Gagal terhubung ke server. Periksa koneksi internet.' };
            } finally {
                // Jeda singkat supaya tidak double-submit jika kamera membaca kode sama berkali-kali.
                setTimeout(() => (this.sedangMemproses = false), 1500);
            }
        },

        async muatFeed() {
            try {
                const res = await fetch('/piket/scan/feed', { headers: { Accept: 'application/json' } });
                this.feed = await res.json();
            } catch (e) {
                // Diamkan -- feed hanya bersifat informatif, bukan kritikal.
            }
        },

        beep() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            osc.type = 'sine';
            osc.frequency.value = 880;
            osc.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        },
    };
};
