<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuesionerAktivasi extends Model
{
    protected $table = 'kuesioner_aktivasi';
    
    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'fasilitas_kampus',
        'sistem_akademik',
        'kualitas_dosen',
        'layanan_administrasi',
        'kepuasan_keseluruhan',
        'saran',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
