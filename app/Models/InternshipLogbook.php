<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipLogbook extends Model
{
    protected $fillable = [
        'internship_id',
        'tanggal',
        'kegiatan',
        'catatan_dosen',
        'created_by_role',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
