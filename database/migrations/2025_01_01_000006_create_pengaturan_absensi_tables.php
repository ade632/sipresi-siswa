<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('radius_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi', 100)->default('Lokasi Utama');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedInteger('radius_meter')->default(100);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('jam_absensi_setting', function (Blueprint $table) {
            $table->id();
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_masuk');               // batas hadir tepat waktu
            $table->time('jam_masuk_terlambat');      // setelah jam ini dianggap terlambat berat / gerbang ditutup
            $table->time('jam_pulang');
            $table->boolean('is_aktif')->default(true); // false = hari libur rutin (contoh: minggu)
            $table->timestamps();
        });

        // key-value store untuk pengaturan umum lain (nama sekolah, logo, dsb)
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('jam_absensi_setting');
        Schema::dropIfExists('radius_sekolah');
    }
};
