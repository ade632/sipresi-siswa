<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // Konstanta kode role, dipakai di middleware CheckRole & seeder.
    const ADMIN = 'admin';
    const GURU_PIKET = 'guru_piket';
    const GURU_BK = 'guru_bk';
    const KEPSEK = 'kepsek';
    const ORTU = 'ortu';

    protected $fillable = ['kode', 'nama'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
