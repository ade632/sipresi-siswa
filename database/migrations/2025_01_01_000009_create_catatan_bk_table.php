<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_bk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('guru_bk_id')->constrained('users');
            $table->date('tanggal');
            $table->enum('jenis', ['konseling', 'pembinaan', 'panggilan_ortu', 'tindak_lanjut']);
            $table->text('catatan');
            $table->enum('status_tindak_lanjut', ['baru', 'proses', 'selesai'])->default('baru');
            $table->timestamps();

            $table->index(['siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_bk');
    }
};
