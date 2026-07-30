<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerangkatPiket extends Model
{
    use HasFactory;

    protected $table = 'perangkat_piket';

    protected $fillable = [
        'nama_device', 'device_fingerprint', 'lokasi_pos', 'is_aktif',
        'didaftarkan_oleh', 'terakhir_dipakai',
    ];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean', 'terakhir_dipakai' => 'datetime'];
    }

    public function didaftarkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'didaftarkan_oleh');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
