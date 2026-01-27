<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KuesionerMahasiswaBaru extends Model
{
    protected $table = 'kuesioner_mahasiswa_baru';

    protected $fillable = [
        'mahasiswa_id',
        'q1','q2','q3','q4','q5','q6','q7',
        'saran',
        'answers',
        'email','prodi','jenis_kelamin','angkatan',
    ];

    protected $casts = [
        'answers' => 'array',
        'q1' => 'integer',
        'q2' => 'integer',
        'q3' => 'integer',
        'q4' => 'integer',
        'q5' => 'integer',
        'q6' => 'integer',
        'q7' => 'integer',
        'angkatan' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
