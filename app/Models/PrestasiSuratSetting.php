<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestasiSuratSetting extends Model
{
    protected $table = 'prestasi_surat_settings';

    protected $fillable = ['jenis_surat', 'format_nomor', 'last_counter', 'reset_year'];

    /**
     * Get setting for a specific letter type.
     */
    public static function getForJenis(string $jenis): ?static
    {
        // Normalize 'surat_tugas' -> 'tugas' etc.
        $key = str_replace('surat_', '', $jenis);
        return static::where('jenis_surat', $key)->first();
    }

    /**
     * Get format for a specific letter type.
     */
    public static function getFormat(string $jenis, string $default = '{counter}/STIH/{tipe}/{month}/{year}'): string
    {
        $setting = static::getForJenis($jenis);
        return $setting ? $setting->format_nomor : $default;
    }
}
