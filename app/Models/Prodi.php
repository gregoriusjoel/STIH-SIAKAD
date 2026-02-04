<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MataKuliah;
use App\Models\Fakultas;

class Prodi extends Model
{
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'fakultas_id',
        'jenjang',
        'status',
    ];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class);
    }
}
