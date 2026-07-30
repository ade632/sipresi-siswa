<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAkunAwalSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            ['role' => Role::ADMIN, 'name' => 'Administrator SIPRESI', 'email' => 'admin@smkn1rl.sch.id'],
            ['role' => Role::GURU_PIKET, 'name' => 'Guru Piket Contoh', 'email' => 'piket@smkn1rl.sch.id'],
            ['role' => Role::GURU_BK, 'name' => 'Guru BK Contoh', 'email' => 'bk@smkn1rl.sch.id'],
            ['role' => Role::KEPSEK, 'name' => 'Kepala Sekolah', 'email' => 'kepsek@smkn1rl.sch.id'],
        ];

        foreach ($akun as $data) {
            $role = Role::where('kode', $data['role'])->firstOrFail();

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'role_id' => $role->id,
                    'name' => $data['name'],
                    // PENTING: ganti password default ini segera setelah instalasi.
                    // Password default sama dengan kode role, contoh: "admin123".
                    'password' => Hash::make($data['role'].'123'),
                    'status_aktif' => true,
                ]
            );
        }

        $this->command->info('Akun awal dibuat. Password default: [kode_role]123 (contoh: admin123). SEGERA GANTI setelah login pertama.');
    }
}
