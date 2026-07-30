<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Membuat backup database otomatis (dipanggil oleh scheduler harian)';

    public function handle(): int
    {
        $dir = storage_path('app/backup');
        File::ensureDirectoryExists($dir);

        $filename = 'sipresi_auto_'.now()->format('Y_m_d_His').'.sql';
        $db = config('database.connections.mysql');

        $process = new Process([
            'mysqldump', '-h', $db['host'], '-P', (string) $db['port'],
            '-u', $db['username'], '--password='.$db['password'],
            $db['database'], '--result-file='."{$dir}/{$filename}",
        ]);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Backup otomatis gagal: '.$process->getErrorOutput());

            return self::FAILURE;
        }

        // Hapus backup otomatis yang lebih tua dari 30 hari agar storage tidak penuh.
        collect(File::files($dir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'sipresi_auto_') && $f->getMTime() < now()->subDays(30)->timestamp)
            ->each(fn ($f) => File::delete($f->getPathname()));

        $this->info("Backup otomatis berhasil: {$filename}");

        return self::SUCCESS;
    }
}
