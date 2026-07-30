<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Token FCM milik user (orang tua/kepsek) untuk push notification.
        // Satu user bisa punya banyak token (multi-device: HP + laptop).
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('token_fcm');
            $table->enum('platform', ['android', 'ios', 'web'])->default('web');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aktivitas'); // contoh: "Menambah siswa", "Login", "Scan absensi"
            $table->string('modul', 50); // contoh: siswa, absensi, pelanggaran
            $table->json('data_before')->nullable();
            $table->json('data_after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['modul', 'created_at']);
        });

        // Notifikasi in-app (riwayat notifikasi yang tampil di lonceng notifikasi
        // pada portal ortu / dashboard, terpisah dari log pengiriman FCM).
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->nullOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['kehadiran', 'terlambat', 'alpa', 'pelanggaran', 'pulang', 'bk', 'umum']);
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('device_tokens');
    }
};
