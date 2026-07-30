<?php

use App\Services\SkorKedisiplinanService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Jobs
|--------------------------------------------------------------------------
| Skor kedisiplinan dihitung ulang setiap malam agar dashboard Kepsek &
| ranking kelas selalu punya data terbaru tanpa membebani query real-time.
| Pastikan cron server sudah mengarah ke `php artisan schedule:run` tiap menit
| (lihat README bagian Deployment).
*/
Schedule::call(function () {
    app(SkorKedisiplinanService::class)->hitungSemuaSiswa();
})->dailyAt('23:30')->name('hitung-skor-kedisiplinan')->withoutOverlapping();

// Tandai siswa yang sama sekali belum absen sampai jam absensi ditutup sebagai alpa.
Schedule::command('absensi:tandai-alpa')->dailyAt('16:00');

// Backup database otomatis setiap malam.
Schedule::command('backup:database')->dailyAt('01:00');
