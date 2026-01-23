<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dosen extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'pendidikan',
        'prodi',
        'phone',
        'address',
        'status',
        'mata_kuliah_ids',
    ];

    protected $casts = [
        'mata_kuliah_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    public function mataKuliahs(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_mata_kuliah');
    }

    public function mahasiswaPa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_pa', 'dosen_id', 'mahasiswa_id')->withTimestamps();
    }
}
