<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('nisn', 20)->unique();
            $table->string('nama', 150);
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('orang_tua_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
            $table->timestamps();

            $table->index(['kelas_id', 'status']);
        });

        // Kartu akses fisik: QR Code dan/atau RFID per siswa.
        // Dipisah dari tabel siswa (bukan kolom langsung) supaya histori
        // penggantian kartu (hilang/rusak) tetap tercatat & kartu lama otomatis nonaktif.
        Schema::create('kartu_akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('tipe', ['qr', 'rfid']);
            $table->string('kode')->unique(); // token QR (uuid) atau UID kartu RFID (hex)
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('tanggal_terbit')->useCurrent();
            $table->foreignId('diterbitkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tipe', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_akses');
        Schema::dropIfExists('siswa');
    }
};
