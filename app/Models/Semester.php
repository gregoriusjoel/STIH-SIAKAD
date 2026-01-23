<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = [
        'nama_semester',
        'tahun_ajaran',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'krs_dapat_diisi',
        'krs_mulai',
        'krs_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'krs_dapat_diisi' => 'boolean',
        'krs_mulai' => 'date',
        'krs_selesai' => 'date',
    ];

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }
}
