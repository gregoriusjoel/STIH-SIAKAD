<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ThesisRevision extends Model
{
    protected $fillable = [
        'thesis_submission_id',
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
        return $this->belongsTo(ThesisSubmission::class, 'thesis_submission_id');
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
