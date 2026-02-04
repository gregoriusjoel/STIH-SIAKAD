<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataKuliah extends Model
{
    protected $fillable = [
        'kode_id',
        'kode_mk',
        'praktikum',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'prodi_id',
        'fakultas_id',
        'deskripsi',
    ];

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }
}
