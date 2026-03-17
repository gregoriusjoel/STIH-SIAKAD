<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisSidangSchedule extends Model
{
    protected $fillable = [
        'thesis_submission_id',
        'sidang_registration_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'ruangan_id',
        'ruangan_manual',
        'pembimbing_id',
        'penguji_1_id',
        'penguji_2_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'waktu_mulai'  => 'datetime:H:i',
        'waktu_selesai'=> 'datetime:H:i',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThesisSubmission::class, 'thesis_submission_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_id');
    }

    public function penguji1(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'penguji_1_id');
    }

    public function penguji2(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'penguji_2_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getRuanganLabelAttribute(): string
    {
        return $this->ruangan?->nama_ruangan ?? $this->ruangan_manual ?? '-';
    }
}
