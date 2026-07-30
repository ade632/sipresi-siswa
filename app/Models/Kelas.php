<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama', 'tingkat', 'jurusan_id', 'tahun_ajaran_id', 'wali_kelas_id', 'wali_kelas_nama'];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function siswaAktif(): HasMany
    {
        return $this->hasMany(Siswa::class)->where('status', 'aktif');
    }

    /** Nama wali kelas untuk ditampilkan: prioritas input manual, fallback ke akun sistem jika ada. */
    public function namaWaliKelas(): string
    {
        return $this->wali_kelas_nama ?: ($this->waliKelas?->name ?? '-');
    }
}
