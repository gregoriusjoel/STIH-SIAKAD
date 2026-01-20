<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $fillable = [
        'krs_id',
        'mahasiswa_id',
        'kelas_mata_kuliah_id',
        'nama',
        'kontak',
        'tanggal',
        'waktu',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu' => 'datetime',
    ];

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }
}
