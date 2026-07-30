<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $table = 'jadwal_piket';

    protected $fillable = ['guru_id', 'hari', 'jam_mulai', 'jam_selesai', 'perangkat_piket_id'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function perangkatPiket(): BelongsTo
    {
        return $this->belongsTo(PerangkatPiket::class, 'perangkat_piket_id');
    }
}
