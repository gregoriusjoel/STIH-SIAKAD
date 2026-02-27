<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataKuliahSemester extends Model
{
    protected $table = 'mata_kuliah_semesters';

    protected $fillable = [
        'semester_id',
        'mata_kuliah_id',
        'status',
        'source_semester_id',
        'activated_at',
        'deactivated_at',
        'meta',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'meta' => 'array',
    ];

    /* ─── Relationships ─── */

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function sourceSemester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'source_semester_id');
    }

    /* ─── Scopes ─── */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHistory($query)
    {
        return $query->where('status', 'history');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeBySemester($query, int $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    /* ─── Helpers ─── */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isHistory(): bool
    {
        return $this->status === 'history';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function deactivate(): void
    {
        $this->update([
            'status' => 'history',
            'deactivated_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update([
            'status' => 'archived',
            'deactivated_at' => $this->deactivated_at ?? now(),
        ]);
    }

    public function reactivate(): void
    {
        $this->update([
            'status' => 'active',
            'activated_at' => now(),
            'deactivated_at' => null,
        ]);
    }
}
