<?php

namespace App\Models;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkripsiSubmission extends Model
{
    use SoftDeletes;

    protected $table = 'skripsi_submissions';

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'judul',
        'deskripsi_proposal',
        'proposal_file_path',
        'requested_supervisor_id',
        'approved_supervisor_id',
        'status',
        'total_bimbingan',
        'logbook_file_path',
        'logbook_original_name',
        'logbook_uploaded_at',
        'eligible_for_sidang_at',
        'revision_approved_at',
        'admin_note',
        'reviewed_by',
    ];

    protected $casts = [
        'status'                => SkripsiStatus::class,
        'eligible_for_sidang_at'=> 'datetime',
        'revision_approved_at'  => 'datetime',
        'logbook_uploaded_at'   => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function requestedSupervisor(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'requested_supervisor_id');
    }

    public function approvedSupervisor(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'approved_supervisor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function guidances(): HasMany
    {
        return $this->hasMany(SkripsiGuidance::class, 'skripsi_submission_id');
    }

    public function approvedGuidances(): HasMany
    {
        return $this->hasMany(SkripsiGuidance::class, 'skripsi_submission_id')->where('status', 'approved');
    }

    public function sidangRegistration(): HasOne
    {
        return $this->hasOne(SkripsiSidangRegistration::class, 'skripsi_submission_id')->latest();
    }

    public function sidangSchedule(): HasOne
    {
        return $this->hasOne(SkripsiSidangSchedule::class, 'skripsi_submission_id')->latest();
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(SkripsiRevision::class, 'skripsi_submission_id')->orderByDesc('created_at');
    }

    public function latestRevision(): HasOne
    {
        return $this->hasOne(SkripsiRevision::class, 'skripsi_submission_id')->latestOfMany();
    }

    public function getIsEligibleForSidangAttribute(): bool
    {
        return $this->total_bimbingan >= 8
            && in_array($this->status, [
                SkripsiStatus::ELIGIBLE_SIDANG,
                SkripsiStatus::SIDANG_REG_DRAFT,
                SkripsiStatus::SIDANG_REG_REJECTED,
            ], true);
    }

    public function getProgressStepAttribute(): int
    {
        return $this->status->step();
    }

    public function getHasLogbookAttribute(): bool
    {
        return !empty($this->logbook_file_path);
    }
}
