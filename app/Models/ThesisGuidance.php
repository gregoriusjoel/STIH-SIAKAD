<?php

namespace App\Models;

use App\Domain\Thesis\Enums\GuidanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisGuidance extends Model
{
    protected $fillable = [
        'thesis_submission_id',
        'dosen_id',
        'tanggal_bimbingan',
        'catatan',
        'file_path',
        'status',
        'catatan_dosen',
        'reviewed_at',
    ];

    protected $casts = [
        'status'           => GuidanceStatus::class,
        'tanggal_bimbingan'=> 'date',
        'reviewed_at'      => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThesisSubmission::class, 'thesis_submission_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
