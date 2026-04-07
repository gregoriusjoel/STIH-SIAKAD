<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkripsiSidangRegistration extends Model
{
    protected $table = 'skripsi_sidang_registrations';

    protected $fillable = [
        'skripsi_submission_id',
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
        return $this->belongsTo(SkripsiSubmission::class, 'skripsi_submission_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(SkripsiSidangFile::class, 'sidang_registration_id');
    }

    public function getFileByType(string|
        \App\Domain\Skripsi\Enums\SidangFileType $type): ?SkripsiSidangFile
    {
        $value = $type instanceof \App\Domain\Skripsi\Enums\SidangFileType ? $type->value : (string) $type;

        return $this->files->first(fn ($f) => (
            $f->file_type instanceof \App\Domain\Skripsi\Enums\SidangFileType
                ? $f->file_type->value
                : (string) $f->file_type
        ) === $value);
    }

    public function hasRequiredFiles(): bool
    {
        $uploaded = $this->files->map(fn($f) => (
            $f->file_type instanceof \App\Domain\Skripsi\Enums\SidangFileType
                ? $f->file_type->value
                : (string) $f->file_type
        ))->toArray();

        $required = array_map(fn($c) => $c->value, \App\Domain\Skripsi\Enums\SidangFileType::required());

        return count(array_diff($required, $uploaded)) === 0;
    }
}
