<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentScore extends Model
{
    protected $fillable = [
        'assignment_id', 'mahasiswa_id', 'graded_by',
        'nilai', 'catatan', 'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'graded_by');
    }
}
