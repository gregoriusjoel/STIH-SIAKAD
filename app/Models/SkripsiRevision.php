<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SkripsiRevision extends Model
{
    protected $table = 'skripsi_revisions';

    protected $fillable = [
        'skripsi_submission_id',
        'revision_file_path',
        'original_name',
        'notes',
        'dosen_notes',
        'approved_by_dosen_id',
        'uploaded_at',
        'approved_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(SkripsiSubmission::class, 'skripsi_submission_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'approved_by_dosen_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->revision_file_path);
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->approved_at !== null;
    }
}
