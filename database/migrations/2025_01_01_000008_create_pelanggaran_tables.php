<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->enum('kategori', ['ringan', 'sedang', 'berat']);
            $table->unsignedInteger('poin');
            $table->timestamps();
        });

        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggaran');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();

            // Snapshot poin saat kejadian dicatat. Sengaja TIDAK hanya join ke
            // jenis_pelanggaran.poin, supaya jika admin mengubah bobot poin di
            // kemudian hari, riwayat pelanggaran lama tidak ikut berubah nilainya.
            $table->unsignedInteger('poin_saat_ini');

            $table->foreignId('dicatat_oleh')->constrained('users');
            $table->string('bukti_foto')->nullable();
            $table->timestamps();

            $table->index(['siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
        Schema::dropIfExists('jenis_pelanggaran');
    }
};
