<?php

namespace App\Models;

use App\Domain\Skripsi\Enums\GuidanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkripsiGuidance extends Model
{
    protected $table = 'skripsi_guidances';

    protected $fillable = [
        'skripsi_submission_id',
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
        return $this->belongsTo(SkripsiSubmission::class, 'skripsi_submission_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
