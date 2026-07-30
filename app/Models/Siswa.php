<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis', 'nisn', 'nama', 'kelas_id', 'jenis_kelamin',
        'tanggal_lahir', 'alamat', 'foto', 'orang_tua_id', 'status',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }

    public function kartuAkses(): HasMany
    {
        return $this->hasMany(KartuAkses::class);
    }

    public function kartuAktif(): HasMany
    {
        return $this->hasMany(KartuAkses::class)->where('is_aktif', true);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function catatanBk(): HasMany
    {
        return $this->hasMany(CatatanBk::class);
    }

    public function skorKedisiplinan(): HasMany
    {
        return $this->hasMany(SkorKedisiplinan::class);
    }

    public function absensiHariIni(): HasOne
    {
        return $this->hasOne(Absensi::class)->whereDate('tanggal', now()->toDateString());
    }

    /** Total poin pelanggaran akumulatif (bisa dibatasi per tahun ajaran jika diperlukan). */
    public function totalPoinPelanggaran(): int
    {
        return (int) $this->pelanggaran()->sum('poin_saat_ini');
    }
}
