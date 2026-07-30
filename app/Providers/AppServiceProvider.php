<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Nama & logo sekolah bisa diatur Admin (menu Pengaturan > Profil Sekolah),
        // dibagikan ke semua view supaya tidak ada lagi teks/logo hardcode.
        View::composer('*', function ($view) {
            $view->with('namaSekolahGlobal', Pengaturan::get('nama_sekolah', 'SMK Negeri 1 Rejang Lebong'));

            // Pastikan key di bawah ini konsisten 'logo_sekolah_path' sesuai controller
            $logoPath = Pengaturan::get('logo_sekolah_path');
            $view->with('logoSekolahGlobal', $logoPath
                ? asset('storage/'.$logoPath)
                : asset('images/logo-sekolah.png'));
        });
    }
}
