<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id', 'name', 'email', 'nip_nik', 'no_hp', 'foto', 'password', 'status_aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /** Anak-anak dari akun orang tua ini (jika role = ortu). */
    public function anak(): HasMany
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function isRole(string $kode): bool
    {
        return $this->role?->kode === $kode;
    }

    public function isAdmin(): bool
    {
        return $this->isRole(Role::ADMIN);
    }

    public function isGuruPiket(): bool
    {
        return $this->isRole(Role::GURU_PIKET);
    }

    public function isGuruBk(): bool
    {
        return $this->isRole(Role::GURU_BK);
    }

    public function isKepsek(): bool
    {
        return $this->isRole(Role::KEPSEK);
    }

    public function isOrtu(): bool
    {
        return $this->isRole(Role::ORTU);
    }
}
