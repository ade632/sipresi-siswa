# SIPRESI SISWA
### Sistem Presensi & Kedisiplinan Siswa — SMK Negeri 1 Rejang Lebong

Aplikasi berbasis Laravel 12 + MySQL + Tailwind CSS + Alpine.js + PWA untuk mengelola
presensi siswa (QR Code & RFID yang dioperasikan Guru Piket), pelanggaran & poin
kedisiplinan, bimbingan konseling (BK), dashboard monitoring Kepala Sekolah, portal
Orang Tua, dan notifikasi push (Firebase) — tanpa WhatsApp.

---

## 📌 Pembaruan Terbaru

- **Kamera Scan Diperbaiki** — sekarang menampilkan status jelas (menyiapkan/aktif/error/izin
  ditolak/tidak ada kamera) beserta tombol "Coba Lagi", bukan layar hitam tanpa keterangan.
  Fallback otomatis ke kamera lain kalau kamera belakang tidak tersedia (umum di laptop/PC).
- **Pengaturan Hari Kerja** — menu **Pengaturan → Radius & Jam Absensi** sekarang menampilkan
  tabel 7 hari sekaligus dengan toggle switch yang jelas, 1 tombol simpan untuk semua hari.
- **Profil Sekolah Bisa Diatur Admin** — menu **Pengaturan** bagian atas: ubah nama sekolah &
  upload logo sendiri, otomatis berlaku di sidebar, halaman login, dan kartu pelajar — tidak
  perlu edit kode lagi.
- **Import Siswa Massal** — menu Data Siswa → "Import Excel".
- **Kartu Pelajar Portrait** — desain ID card portrait (bukan landscape), data rapi tidak
  tumpang tindih, logo & nama sekolah otomatis ambil dari Profil Sekolah.
- **Device Piket Otomatis** — tidak perlu daftar manual, kamera langsung aktif begitu halaman
  Scan Absensi dibuka.
- **Wali Kelas Manual** — menu Data Kelas sekarang punya input teks bebas "Nama Wali Kelas"
  (tidak harus punya akun sistem), ditampilkan juga di Rekap Absensi Harian.

---

## 🔄 Cara Update Instalasi yang Sudah Berjalan

Kalau Anda sudah pernah instal versi sebelumnya, cukup:

1. Ekstrak zip terbaru ini **menimpa** folder project lama (jangan hapus folder `.env`,
   `vendor/`, `node_modules/`, atau isi `storage/app/public/` milik Anda — file-file itu
   tidak ada di dalam zip sehingga aman, tidak akan tertimpa/terhapus).
2. Jalankan:
   ```bash
   composer install
   php artisan migrate
   ```
   *(ada 1 migration baru untuk kolom wali kelas manual — `migrate` akan otomatis menerapkannya
   tanpa menghapus data yang sudah ada)*
3. **Wajib manual** (karena ini data, bukan kode): kalau belum pernah diubah, matikan hari
   Sabtu di menu **Pengaturan → Hari Kerja & Jam Absensi**.
4. Refresh browser (Ctrl+F5) untuk memastikan tampilan baru termuat.

---


## 1. Kebutuhan Sistem

| Komponen | Versi Minimum |
|---|---|
| PHP | 8.3+ (ekstensi: mbstring, pdo_mysql, gd, zip, bcmath, xml, curl) |
| Composer | 2.x |
| MySQL / MariaDB | 8.0 / 10.6+ |
| Node.js & NPM | 18+ |
| Web server | Nginx/Apache, atau `php artisan serve` untuk uji coba lokal |

> **Catatan penting:** proyek ini dibuat dengan seluruh source code Laravel ditulis
> langsung (bukan hasil `composer create-project`), karena environment pembuatannya
> tidak memiliki akses ke Packagist. Struktur & konvensi 100% mengikuti Laravel 12
> standar, jadi `composer install` akan bekerja normal di server/komputer Anda yang
> punya akses internet penuh.

---

## 2. Instalasi Lokal (XAMPP/Laragon/Docker/native)

```bash
# 1. Masuk ke folder proyek
cd sipresi-siswa

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database MySQL kosong, contoh nama: sipresi_siswa
#    lalu sesuaikan kredensial di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 6. Jalankan migration + seeder data awal
php artisan migrate --seed

# 7. Buat symlink storage (untuk foto siswa, bukti pelanggaran, dll)
php artisan storage:link

# 8. Install dependency frontend & build asset
npm install
npm run build
# atau untuk mode development dengan hot-reload:
npm run dev

# 9. Jalankan server lokal
php artisan serve
```

Buka `http://localhost:8000` — akan diarahkan ke halaman login.

### Akun awal (dari seeder `UserAkunAwalSeeder`)

| Role | Email | Password |
|---|---|---|
| Administrator | admin@smkn1rl.sch.id | admin123 |
| Guru Piket | piket@smkn1rl.sch.id | guru_piket123 |
| Guru BK | bk@smkn1rl.sch.id | guru_bk123 |
| Kepala Sekolah | kepsek@smkn1rl.sch.id | kepsek123 |

**⚠️ SEGERA ganti password ini setelah instalasi** (melalui menu Admin → Pengguna → Reset Sandi).

Akun Orang Tua dibuat otomatis setiap kali Admin menambahkan siswa baru (password
default = NISN siswa).

---

## 3. Konfigurasi Penting

### 3.1 Radius Sekolah & Jam Absensi
Login sebagai Admin → menu **Radius & Jam Absensi**. Data default dari seeder memakai
koordinat contoh — **wajib diubah** ke koordinat asli SMK Negeri 1 Rejang Lebong
(ambil dari Google Maps, klik kanan titik gerbang sekolah → salin koordinat).

### 3.2 Perangkat Piket (Wajib sebelum bisa scan absensi)
Alur absensi sudah disesuaikan: **siswa hanya membawa kartu QR/RFID, Guru Piket yang
melakukan scan** dari device piket (tablet/laptop/HP) yang harus didaftarkan dulu:

1. Buka aplikasi dari device yang akan dipakai piket.
2. Login sebagai Admin (atau minta Admin membuka dari device tersebut).
3. Menu **Perangkat Piket** → klik "Ambil Fingerprint Device Ini" → isi nama device → Daftarkan.
4. Device tersebut sekarang bisa dipakai Guru Piket untuk scan (menu **Scan Absensi**).

Device yang belum terdaftar akan ditolak otomatis saat mencoba scan (proteksi geofencing).

### 3.3 Push Notification (Firebase Cloud Messaging)
Menggantikan WhatsApp Gateway sesuai requirement — notifikasi dikirim langsung ke
browser/HP orang tua.

1. Buat project di [Firebase Console](https://console.firebase.google.com).
2. Aktifkan **Cloud Messaging**.
3. Project Settings → Service Accounts → **Generate new private key** → simpan sebagai
   `storage/app/firebase/service-account.json`.
4. Project Settings → Cloud Messaging → **Web Push certificates** → generate key pair (VAPID key).
5. Isi seluruh variabel `VITE_FIREBASE_*` dan `FIREBASE_PROJECT_ID` di `.env`.
6. Isi juga nilai yang sama secara manual di `public/firebase-messaging-sw.js`
   (service worker tidak bisa membaca `.env`, harus di-hardcode di file tersebut).
7. `npm run build` ulang setelah mengisi env.

Orang tua mengaktifkan notifikasi lewat tombol "Aktifkan Notifikasi Push" di halaman
**Portal Orang Tua → Notifikasi**.

### 3.4 Cetak Kartu QR Siswa
Setiap siswa baru otomatis mendapat kartu QR (token UUID). Cetak dari:
- Per siswa: Data Siswa → tombol "Kartu QR"
- Per kelas (batch): Data Kelas → tombol "Cetak Kartu"

Ukuran kartu didesain standar ID card (85.6mm x 54mm), siap dicetak & dilaminasi.

### 3.5 Kartu RFID
UID kartu RFID diinput manual saat menambah/mengubah data siswa (tempelkan kartu ke
RFID reader USB yang terpasang di komputer — reader bertipe **keyboard emulation
(HID)** akan otomatis mengetik UID ke kolom yang sedang fokus).

---

## 4. Struktur Folder Penting

```
app/
├── Console/Commands/       → Artisan command custom (tandai alpa, backup otomatis)
├── Http/Controllers/       → Dikelompokkan per modul (Admin, Piket, BK, Pelanggaran, Laporan, Ortu, Api)
├── Http/Middleware/        → CheckRole (RBAC), EnsurePerangkatPiketTerdaftar (geofencing)
├── Models/                 → 21 model Eloquent
├── Services/               → Business logic (AbsensiService, GeofencingService, dst)
├── Repositories/           → Query kompleks (Siswa, Absensi)
├── Notifications/          → Push notification (FCM) + in-app notification
└── Exports/                → Export Excel (Maatwebsite)

database/
├── migrations/             → 12 file migration
└── seeders/                → Role, User awal, Akademik, Jenis Pelanggaran

resources/
├── views/                  → Blade + Tailwind, dikelompokkan per modul
├── js/                     → Alpine.js, scan-qr.js (kamera QR + RFID), Firebase
└── css/                    → Tailwind entry

public/
├── manifest.json           → Web App Manifest (PWA)
├── sw.js                   → Service Worker (app shell caching)
└── firebase-messaging-sw.js → Service worker khusus push notification
```

---

## 5. Deployment ke VPS / Hosting

```bash
# Di server (Ubuntu + Nginx + PHP-FPM + MySQL contoh)
git clone <repo-anda> /var/www/sipresi-siswa
cd /var/www/sipresi-siswa
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
# edit .env: APP_ENV=production, APP_DEBUG=false, DB_*, FIREBASE_*
php artisan migrate --seed --force
php artisan storage:link
npm install && npm run build

# Set permission
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Arahkan document root Nginx ke folder public/
```

### Cron Job (wajib untuk fitur terjadwal)
Tambahkan baris berikut di crontab server (`crontab -e`):

```
* * * * * cd /var/www/sipresi-siswa && php artisan schedule:run >> /dev/null 2>&1
```

Ini menjalankan:
- **23:30** setiap hari — hitung ulang skor kedisiplinan seluruh siswa
- **16:00** setiap hari — tandai otomatis siswa yang belum absen sebagai Alpa
- **01:00** setiap hari — backup database otomatis

### Queue Worker (untuk push notification tidak memblokir request)
```
php artisan queue:work --daemon
```
Disarankan dijalankan via Supervisor agar otomatis restart jika crash.

### HTTPS Wajib
Geolocation API dan kamera browser (untuk scan QR) **hanya berfungsi di HTTPS**
(kecuali localhost). Pastikan VPS sudah dipasangi SSL (gratis via Let's Encrypt/Certbot)
sebelum digunakan untuk scan absensi sungguhan.

---

## 6. Instalasi PWA di Perangkat

- **Android (Chrome)**: buka situs → menu titik tiga → "Tambahkan ke layar Utama" / "Instal aplikasi".
- **iPhone (Safari)**: buka situs → tombol Share → "Tambah ke Layar Utama".
- **Windows (Chrome/Edge)**: ikon instal muncul otomatis di address bar.

---

## 7. Catatan Pengembangan Lanjutan

Beberapa hal yang disederhanakan dan bisa dikembangkan lebih lanjut sesuai kebutuhan:

- **Backup/restore** memakai `mysqldump` via `Symfony\Process` — untuk kebutuhan produksi
  skala besar, pertimbangkan `spatie/laravel-backup` (mendukung upload otomatis ke cloud).
- **Repository Pattern** hanya diterapkan untuk entitas dengan query kompleks (Siswa,
  Absensi) sesuai prinsip Clean Architecture yang dijelaskan di tahap analisis —
  CRUD sederhana (Kelas, Jurusan) sengaja langsung lewat Eloquent di Controller.
- **RFID Reader**: sistem ini mengasumsikan reader bertipe *keyboard emulation (HID)*
  yang paling umum & tidak butuh driver tambahan. Jika reader Anda memakai protokol
  serial/Wiegand khusus, perlu proses jembatan (bridge) tambahan sebelum data UID
  bisa masuk ke browser.
- **Ranking kelas** di dashboard Kepsek saat ini menampilkan `Kelas #ID` — silakan
  eager-load nama kelas asli di `AbsensiRepository::rankingKedisiplinanKelas()` sesuai
  preferensi tampilan Anda.

---

## 8. Lisensi

Dibangun khusus untuk SMK Negeri 1 Rejang Lebong. Bebas dimodifikasi sesuai kebutuhan
internal sekolah.
#   s i p r e s i - s i s w a  
 