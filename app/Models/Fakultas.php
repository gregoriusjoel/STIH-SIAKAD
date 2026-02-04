<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Prodi;

class Fakultas extends Model
{
    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
        'status',
    ];

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }
}
