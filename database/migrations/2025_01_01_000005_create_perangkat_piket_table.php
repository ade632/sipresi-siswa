<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Whitelist device yang boleh dipakai untuk scan absensi.
        // Karena yang men-scan sekarang adalah Guru Piket (bukan siswa),
        // validasi geofencing cukup dilakukan terhadap device piket yang
        // sudah didaftarkan Admin -> jauh lebih sulit dimanipulasi
        // dibanding memvalidasi ratusan HP siswa.
        Schema::create('perangkat_piket', function (Blueprint $table) {
            $table->id();
            $table->string('nama_device', 100); // contoh: "Pos Gerbang Utama - Tablet 1"
            $table->string('device_fingerprint')->unique(); // hash browser/device
            $table->string('lokasi_pos', 100)->nullable(); // gerbang utama, gerbang belakang, dll
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('didaftarkan_oleh')->constrained('users');
            $table->timestamp('terakhir_dipakai')->nullable();
            $table->timestamps();
        });

        Schema::create('jadwal_piket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('perangkat_piket_id')->nullable()->constrained('perangkat_piket')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket');
        Schema::dropIfExists('perangkat_piket');
    }
};
