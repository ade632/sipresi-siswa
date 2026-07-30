<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'metode',
        'perangkat_piket_id', 'latitude', 'longitude', 'jarak_meter', 'ip_address',
        'dicatat_oleh', 'keterangan', 'lampiran',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function perangkatPiket(): BelongsTo
    {
        return $this->belongsTo(PerangkatPiket::class, 'perangkat_piket_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', now()->toDateString());
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
