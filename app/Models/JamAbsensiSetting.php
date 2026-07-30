<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamAbsensiSetting extends Model
{
    use HasFactory;

    protected $table = 'jam_absensi_setting';

    protected $fillable = ['hari', 'jam_masuk', 'jam_masuk_terlambat', 'jam_pulang', 'is_aktif'];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean'];
    }

    public static function hariIni(): ?self
    {
        $hariMap = ['sunday' => 'minggu', 'monday' => 'senin', 'tuesday' => 'selasa',
            'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu'];

        $hari = $hariMap[strtolower(now()->format('l'))];

        return static::where('hari', $hari)->first();
    }
}
