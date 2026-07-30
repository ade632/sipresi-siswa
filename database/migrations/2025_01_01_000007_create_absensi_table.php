<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'dispensasi', 'alpa'])->default('alpa');
            $table->enum('metode', ['qr', 'rfid', 'manual'])->default('manual');

            // Lokasi & keamanan diambil dari device Guru Piket (bukan device siswa),
            // sesuai alur revisi: siswa hanya membawa kartu, Guru Piket yang scan.
            $table->foreignId('perangkat_piket_id')->nullable()->constrained('perangkat_piket')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('jarak_meter')->nullable();
            $table->string('ip_address', 45)->nullable();

            // Guru piket wajib ada, karena dialah yang melakukan aksi scan/input.
            $table->foreignId('dicatat_oleh')->constrained('users');

            $table->text('keterangan')->nullable(); // alasan izin/sakit/dispensasi, atau catatan lain
            $table->string('lampiran')->nullable();  // surat izin/dokter discan, opsional
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']); // 1 siswa hanya 1 record per hari
            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
