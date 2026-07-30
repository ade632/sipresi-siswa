<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $fillable = ['key', 'value'];
    public $timestamps = true;

    // Helper global untuk mengambil nilai berdasarkan key
    public static function get($key, $default = null)
    {
        $pengaturan = self::where('key', $key)->first();
        return $pengaturan ? $pengaturan->value : $default;
    }

    // Helper global untuk menyimpan/memperbarui nilai berdasarkan key
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}