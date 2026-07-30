<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadiusSekolah extends Model
{
    use HasFactory;

    protected $table = 'radius_sekolah';

    protected $fillable = ['nama_lokasi', 'latitude', 'longitude', 'radius_meter', 'is_aktif'];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean'];
    }

    public static function aktif()
    {
        return static::where('is_aktif', true)->get();
    }
}
