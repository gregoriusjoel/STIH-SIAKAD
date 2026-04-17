<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
        'status',
    ];

    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }
}
