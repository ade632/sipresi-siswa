<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\JenisPelanggaranController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PerangkatPiketController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BK\CatatanBkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Laporan\LaporanController;
use App\Http\Controllers\Ortu\PortalOrtuController;
use App\Http\Controllers\Pelanggaran\PelanggaranController;
use App\Http\Controllers\Piket\AbsensiManualController;
use App\Http\Controllers\Piket\RekapAbsensiController;
use App\Http\Controllers\Piket\ScanAbsensiController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATOR
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::ADMIN)->prefix('admin')->name('admin.')->group(function () {
        
        // Route API internal untuk polling real-time di Dashboard Admin
        Route::get('api/pelanggaran-terbaru', [DashboardController::class, 'apiPelanggaranTerbaru'])->name('api.pelanggaran');

        Route::resource('siswa', SiswaController::class)->except(['show']);
        Route::delete('siswa/hapus-per-kelas', [SiswaController::class, 'hapusPerKelas'])->name('siswa.hapus-per-kelas');
        Route::get('siswa/import', [SiswaController::class, 'importForm'])->name('siswa.import.form');
        Route::get('siswa/import/template', [SiswaController::class, 'template'])->name('siswa.import.template');
        
        Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import.store');

        // Route Cetak Kartu QR Individu Siswa
        Route::get('siswa/{siswa}/cetak-kartu', [SiswaController::class, 'cetakKartu'])->name('siswa.cetak-kartu');
        
        Route::get('kelas/{kelas}/cetak-kartu', [SiswaController::class, 'cetakKartuKelas'])->name('kelas.cetak-kartu');
        Route::get('kelas/{kelas}/cetak-kartu-batch', [SiswaController::class, 'cetakKartuKelas'])->name('siswa.cetak-kartu-batch');

        Route::resource('kelas', KelasController::class)->except(['show']);
        Route::resource('jurusan', JurusanController::class)->only(['index', 'store', 'update', 'destroy']);
        
        Route::resource('tahun-ajaran', TahunAjaranController::class)->only(['index', 'store', 'destroy']);
        Route::patch('tahun-ajaran/{tahunAjaran}/aktifkan', [TahunAjaranController::class, 'aktifkan'])->name('tahun-ajaran.aktifkan');

        Route::resource('jenis-pelanggaran', JenisPelanggaranController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('perangkat-piket', [PerangkatPiketController::class, 'index'])->name('perangkat-piket.index');
        Route::post('perangkat-piket', [PerangkatPiketController::class, 'store'])->name('perangkat-piket.store');
        Route::post('perangkat-piket/{perangkatPiket}/toggle', [PerangkatPiketController::class, 'toggleAktif'])->name('perangkat-piket.toggle');
        Route::delete('perangkat-piket/{perangkatPiket}', [PerangkatPiketController::class, 'destroy'])->name('perangkat-piket.destroy');

        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('pengaturan/radius', [PengaturanController::class, 'simpanRadius'])->name('pengaturan.radius');
        Route::delete('pengaturan/radius/{id}', [PengaturanController::class, 'destroyRadius'])->name('pengaturan.radius.destroy');
        Route::post('pengaturan/jam-absensi', [PengaturanController::class, 'simpanJamAbsensi'])->name('pengaturan.jam-absensi');
        Route::post('pengaturan/profil-sekolah', [PengaturanController::class, 'simpanProfilSekolah'])->name('pengaturan.profil-sekolah');
        Route::post('pengaturan/kepala-sekolah', [PengaturanController::class, 'simpanKepalaSekolah'])->name('pengaturan.kepala-sekolah');
        Route::get('kalender-akademik', [PengaturanController::class, 'kalenderAkademik'])->name('kalender-akademik.index');
        Route::post('kalender-akademik', [PengaturanController::class, 'simpanKalender'])->name('kalender-akademik.store');

        // PENGELOLAAN PENGGUNA
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{user}/toggle', [UserController::class, 'toggleAktif'])->name('users.toggle');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');

        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup', [BackupController::class, 'buatBackup'])->name('backup.store');
        Route::get('backup/{filename}/unduh', [BackupController::class, 'unduh'])->name('backup.unduh');
        Route::delete('backup/{filename}', [BackupController::class, 'hapus'])->name('backup.hapus');
    });

    /*
    |--------------------------------------------------------------------------
    | GURU PIKET
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::GURU_PIKET.','.Role::ADMIN)->prefix('piket')->name('piket.')->group(function () {
        Route::get('scan', [ScanAbsensiController::class, 'index'])->name('scan.index');
        Route::get('scan/feed', [ScanAbsensiController::class, 'feedHariIni'])->name('scan.feed');
        Route::post('scan', [ScanAbsensiController::class, 'proses'])
            ->middleware('perangkat.piket')
            ->name('scan.proses');

        Route::get('manual', [AbsensiManualController::class, 'index'])->name('manual.index');
        Route::post('manual', [AbsensiManualController::class, 'store'])->name('manual.store');
        Route::post('manual/massal', [AbsensiManualController::class, 'storeMassal'])->name('manual.massal');

        Route::get('rekap-harian', [RekapAbsensiController::class, 'harian'])->name('rekap.harian');
        Route::get('rekap-keterlambatan', [RekapAbsensiController::class, 'keterlambatan'])->name('rekap.keterlambatan');
        Route::get('belum-hadir', [RekapAbsensiController::class, 'belumHadir'])->name('rekap.belum-hadir');
    });

    /*
    |--------------------------------------------------------------------------
    | PELANGGARAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::GURU_PIKET.','.Role::GURU_BK.','.Role::ADMIN)->prefix('pelanggaran')->name('pelanggaran.')->group(function () {
        Route::get('/', [PelanggaranController::class, 'index'])->name('index');
        Route::get('create', [PelanggaranController::class, 'create'])->name('create');
        Route::post('/', [PelanggaranController::class, 'store'])->name('store');
        Route::delete('{pelanggaran}', [PelanggaranController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | GURU BK
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::GURU_BK.','.Role::ADMIN)->prefix('bk')->name('bk.')->group(function () {
        Route::get('/', [CatatanBkController::class, 'index'])->name('index');
        Route::get('siswa/{siswa}', [CatatanBkController::class, 'detail'])->name('detail');
        Route::post('siswa/{siswa}/catatan', [CatatanBkController::class, 'storeCatatan'])->name('catatan.store');
        Route::post('catatan/{catatanBk}/status', [CatatanBkController::class, 'updateStatusTindakLanjut'])->name('catatan.status');
    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::ADMIN.','.Role::GURU_PIKET.','.Role::GURU_BK.','.Role::KEPSEK)
        ->prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('absensi/pdf', [LaporanController::class, 'absensiPdf'])->name('absensi.pdf');
            Route::get('absensi/excel', [LaporanController::class, 'absensiExcel'])->name('absensi.excel');
            Route::get('pelanggaran/pdf', [LaporanController::class, 'pelanggaranPdf'])->name('pelanggaran.pdf');
            Route::get('pelanggaran/excel', [LaporanController::class, 'pelanggaranExcel'])->name('pelanggaran.excel');
            Route::get('siswa/{siswa}/pdf', [LaporanController::class, 'perSiswaPdf'])->name('per-siswa.pdf');
        });

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA / WALI
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:'.Role::ORTU)->prefix('ortu')->name('ortu.')->group(function () {
        Route::get('portal', [PortalOrtuController::class, 'index'])->name('portal');
        Route::get('notifikasi', [PortalOrtuController::class, 'notifikasi'])->name('notifikasi');
        Route::post('notifikasi/{notifikasi}/baca', [PortalOrtuController::class, 'tandaiDibaca'])->name('notifikasi.baca');
    });
});