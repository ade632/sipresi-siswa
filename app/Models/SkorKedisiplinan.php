<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkorKedisiplinan extends Model
{
    use HasFactory;

    protected $table = 'skor_kedisiplinan';

    protected $fillable = [
        'siswa_id', 'periode', 'persen_kehadiran', 'persen_ketepatan',
        'total_poin_pelanggaran', 'status', 'dihitung_pada',
    ];

    protected function casts(): array
    {
        return ['dihitung_pada' => 'datetime'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            'sangat_baik' => '🟢 Sangat Baik',
            'baik' => '🔵 Baik',
            'cukup' => '🟡 Cukup',
            'perlu_pembinaan' => '🔴 Perlu Pembinaan Khusus',
            default => '-',
        };
    }
}
