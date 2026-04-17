<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriRuangan extends Model
{
    protected $table = 'kategori_ruangans';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'warna_badge',
        'urutan',
        'status',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    protected $appends = [
        'badge_color',
        'icon',
    ];

    // Relasi dengan Ruangan
    public function ruangans(): HasMany
    {
        return $this->hasMany(Ruangan::class, 'kategori_id');
    }

    // Scope untuk kategori aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama_kategori');
    }

    // Get badge color class
    public function getBadgeColorAttribute(): string
    {
        return match($this->warna_badge) {
            'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'green' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'red' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'pink' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        };
    }

    // Get icon for kategori
    public function getIconAttribute(): string
    {
        return match(strtolower($this->nama_kategori)) {
            'kelas' => 'fa-chalkboard-user',
            'praktikum' => 'fa-flask',
            'sidang' => 'fa-gavel',
            'laboratorium' => 'fa-microscope',
            default => 'fa-door-open',
        };
    }
}
