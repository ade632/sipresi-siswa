<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_log';

    public $timestamps = true;

    protected $fillable = ['user_id', 'aktivitas', 'modul', 'data_before', 'data_after', 'ip_address'];

    protected function casts(): array
    {
        return ['data_before' => 'array', 'data_after' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function catat(string $aktivitas, string $modul, ?array $before = null, ?array $after = null): void
    {
        static::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'data_before' => $before,
            'data_after' => $after,
            'ip_address' => request()->ip(),
        ]);
    }
}
