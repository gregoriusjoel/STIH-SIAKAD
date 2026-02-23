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
        'pertemuan',
        'nama',
        'kontak',
        'tanggal',
        'waktu',
        'status',
        'keterangan',
        'student_lat',
        'student_lng',
        'distance_meters',
        'presence_mode',
        'reason_category',
        'reason_detail',
        'campus_lat',
        'campus_lng',
        'radius_meters',
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
