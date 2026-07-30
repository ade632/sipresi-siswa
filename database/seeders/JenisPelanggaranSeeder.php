<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use Illuminate\Database\Seeder;

class JenisPelanggaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Terlambat masuk sekolah', 'kategori' => 'ringan', 'poin' => 5],
            ['nama' => 'Tidak memakai atribut lengkap', 'kategori' => 'ringan', 'poin' => 10],
            ['nama' => 'Tidak mengerjakan tugas', 'kategori' => 'ringan', 'poin' => 5],
            ['nama' => 'Membolos jam pelajaran', 'kategori' => 'sedang', 'poin' => 20],
            ['nama' => 'Berpakaian tidak sesuai aturan', 'kategori' => 'ringan', 'poin' => 10],
            ['nama' => 'Merokok di lingkungan sekolah', 'kategori' => 'berat', 'poin' => 50],
            ['nama' => 'Membawa/menggunakan barang terlarang', 'kategori' => 'berat', 'poin' => 75],
            ['nama' => 'Perkelahian di lingkungan sekolah', 'kategori' => 'berat', 'poin' => 100],
            ['nama' => 'Mencontek saat ujian', 'kategori' => 'sedang', 'poin' => 15],
            ['nama' => 'Merusak fasilitas sekolah', 'kategori' => 'sedang', 'poin' => 25],
        ];

        foreach ($data as $item) {
            JenisPelanggaran::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
