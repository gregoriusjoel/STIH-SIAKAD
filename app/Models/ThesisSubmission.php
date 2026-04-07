<?php

namespace App\Models;

use App\Domain\Thesis\Enums\ThesisStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisSubmission extends Model
{
    use SoftDeletes;

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
        'status'                => ThesisStatus::class,
        'eligible_for_sidang_at'=> 'datetime',
        'revision_approved_at'  => 'datetime',
        'logbook_uploaded_at'   => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────

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
        return $this->hasMany(ThesisGuidance::class);
    }

    public function approvedGuidances(): HasMany
    {
        return $this->hasMany(ThesisGuidance::class)->where('status', 'approved');
    }

    public function sidangRegistration(): HasOne
    {
        return $this->hasOne(ThesisSidangRegistration::class)->latest();
    }

    public function sidangSchedule(): HasOne
    {
        return $this->hasOne(ThesisSidangSchedule::class)->latest();
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ThesisRevision::class)->orderByDesc('created_at');
    }

    public function latestRevision(): HasOne
    {
        return $this->hasOne(ThesisRevision::class)->latestOfMany();
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getIsEligibleForSidangAttribute(): bool
    {
        return $this->total_bimbingan >= 8
            && in_array($this->status, [
                ThesisStatus::ELIGIBLE_SIDANG,
                ThesisStatus::SIDANG_REG_DRAFT,
                ThesisStatus::SIDANG_REG_REJECTED,
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
