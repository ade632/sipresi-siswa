<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Nama wali kelas diisi manual (teks bebas), karena tidak semua wali kelas
            // punya akun login di sistem ini (banyak guru mapel biasa jadi wali kelas
            // tanpa perlu akses sistem). Kolom wali_kelas_id (FK users) tetap ada,
            // dipakai opsional kalau wali kelas tsb kebetulan juga user terdaftar.
            $table->string('wali_kelas_nama', 150)->nullable()->after('wali_kelas_id');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('wali_kelas_nama');
        });
    }
};
