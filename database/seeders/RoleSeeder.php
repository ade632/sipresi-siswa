<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['kode' => Role::ADMIN, 'nama' => 'Administrator'],
            ['kode' => Role::GURU_PIKET, 'nama' => 'Guru Piket'],
            ['kode' => Role::GURU_BK, 'nama' => 'Guru BK'],
            ['kode' => Role::KEPSEK, 'nama' => 'Kepala Sekolah'],
            ['kode' => Role::ORTU, 'nama' => 'Orang Tua/Wali'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['kode' => $role['kode']], $role);
        }
    }
}
