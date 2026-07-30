<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Backup/restore database sederhana berbasis mysqldump.
 * Untuk penggunaan produksi skala besar, pertimbangkan spatie/laravel-backup
 * yang juga mendukung upload otomatis ke cloud storage (S3/Google Drive).
 */
class BackupController extends Controller
{
    protected string $backupDir = 'app/backup';

    public function index()
    {
        $path = storage_path($this->backupDir);
        File::ensureDirectoryExists($path);

        $files = collect(File::files($path))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->map(fn ($f) => [
                'nama' => $f->getFilename(),
                'ukuran' => round($f->getSize() / 1024 / 1024, 2).' MB',
                'tanggal' => date('d-m-Y H:i', $f->getMTime()),
            ]);

        return view('pengaturan.backup', ['files' => $files]);
    }

    public function buatBackup()
    {
        $path = storage_path($this->backupDir);
        File::ensureDirectoryExists($path);

        $filename = 'sipresi_'.now()->format('Y_m_d_His').'.sql';
        $fullPath = "{$path}/{$filename}";

        $db = config('database.connections.mysql');

        $process = new Process([
            'mysqldump',
            '-h', $db['host'],
            '-P', (string) $db['port'],
            '-u', $db['username'],
            '--password='.$db['password'],
            $db['database'],
            '--result-file='.$fullPath,
        ]);
        $process->run();

        if (! $process->isSuccessful()) {
            return back()->with('error', 'Backup gagal: '.$process->getErrorOutput());
        }

        AuditLog::catat("Membuat backup database: {$filename}", 'backup');

        return back()->with('success', "Backup berhasil dibuat: {$filename}");
    }

    public function unduh(string $filename)
    {
        $path = storage_path("{$this->backupDir}/{$filename}");
        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }

    public function hapus(string $filename)
    {
        $path = storage_path("{$this->backupDir}/{$filename}");

        if (File::exists($path)) {
            File::delete($path);
            AuditLog::catat("Menghapus file backup: {$filename}", 'backup');
        }

        return back()->with('success', 'File backup berhasil dihapus.');
    }
}
