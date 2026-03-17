<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThesisSidangRegistration extends Model
{
    protected $fillable = [
        'thesis_submission_id',
        'status',
        'notes',
        'admin_note',
        'verified_by',
        'submitted_at',
        'verified_at',
        'rejected_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
        'rejected_at'  => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThesisSubmission::class, 'thesis_submission_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ThesisSidangFile::class, 'sidang_registration_id');
    }

    public function getFileByType(string|
        \App\Domain\Thesis\Enums\SidangFileType $type): ?ThesisSidangFile
    {
        $value = $type instanceof \App\Domain\Thesis\Enums\SidangFileType ? $type->value : (string) $type;

        return $this->files->first(fn ($f) => (
            $f->file_type instanceof \App\Domain\Thesis\Enums\SidangFileType
                ? $f->file_type->value
                : (string) $f->file_type
        ) === $value);
    }

    public function hasRequiredFiles(): bool
    {
        $uploaded = $this->files->map(fn($f) => (
            $f->file_type instanceof \App\Domain\Thesis\Enums\SidangFileType
                ? $f->file_type->value
                : (string) $f->file_type
        ))->toArray();

        $required = array_map(fn($c) => $c->value, \App\Domain\Thesis\Enums\SidangFileType::required());

        return count(array_diff($required, $uploaded)) === 0;
    }
}
