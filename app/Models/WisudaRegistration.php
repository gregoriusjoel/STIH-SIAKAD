<?php

namespace App\Models;

use App\Domain\Wisuda\Enums\WisudaDocumentType;
use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WisudaRegistration extends Model
{
    protected $table = 'wisuda_registrations';

    protected $fillable = [
        'mahasiswa_id',
        'skripsi_submission_id',
        'wisuda_batch_id',
        'no_hp',
        'email_aktif',
        'status',
        'rejection_note',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'status'       => WisudaRegistrationStatus::class,
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    // ── Relations ────────────────────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function skripsiSubmission(): BelongsTo
    {
        return $this->belongsTo(SkripsiSubmission::class, 'skripsi_submission_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(WisudaBatch::class, 'wisuda_batch_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(WisudaDocument::class, 'wisuda_registration_id');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    public function getDocumentByType(string|WisudaDocumentType $type): ?WisudaDocument
    {
        $value = $type instanceof WisudaDocumentType ? $type->value : (string) $type;

        return $this->documents->first(fn ($f) => (
            $f->file_type instanceof WisudaDocumentType
                ? $f->file_type->value
                : (string) $f->file_type
        ) === $value);
    }

    public function hasRequiredDocuments(): bool
    {
        $uploaded = $this->documents->map(fn($f) => (
            $f->file_type instanceof WisudaDocumentType
                ? $f->file_type->value
                : (string) $f->file_type
        ))->toArray();

        $required = array_map(fn($c) => $c->value, WisudaDocumentType::required());

        return count(array_diff($required, $uploaded)) === 0;
    }

    public function isActive(): bool
    {
        return in_array($this->status, WisudaRegistrationStatus::activeStatuses(), true);
    }
}
