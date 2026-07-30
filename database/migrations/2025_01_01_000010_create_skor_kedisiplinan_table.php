<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel derived/cache: dihitung ulang secara berkala (scheduled job harian)
        // supaya dashboard Kepsek & ranking kelas tidak perlu agregasi berat
        // secara real-time setiap kali halaman dibuka.
        Schema::create('skor_kedisiplinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->string('periode', 20); // format: 2026-07 (bulanan)
            $table->decimal('persen_kehadiran', 5, 2)->default(0);
            $table->decimal('persen_ketepatan', 5, 2)->default(0);
            $table->unsignedInteger('total_poin_pelanggaran')->default(0);
            $table->enum('status', ['sangat_baik', 'baik', 'cukup', 'perlu_pembinaan'])->default('baik');
            $table->timestamp('dihitung_pada')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_kedisiplinan');
    }
};
