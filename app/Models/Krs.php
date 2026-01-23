<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Krs extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'kelas_mata_kuliah_id',
        'kelas_id',
        'status',
        'keterangan',
        'ambil_mk',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kelasMataKuliah(): BelongsTo
    {
        return $this->belongsTo(KelasMataKuliah::class, 'kelas_mata_kuliah_id');
    }

    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class);
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }
}
