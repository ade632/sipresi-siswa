<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuAkses extends Model
{
    use HasFactory;

    protected $table = 'kartu_akses';

    protected $fillable = ['siswa_id', 'tipe', 'kode', 'is_aktif', 'tanggal_terbit', 'diterbitkan_oleh'];

    protected function casts(): array
    {
        return ['is_aktif' => 'boolean', 'tanggal_terbit' => 'datetime'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function diterbitkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterbitkan_oleh');
    }
}
