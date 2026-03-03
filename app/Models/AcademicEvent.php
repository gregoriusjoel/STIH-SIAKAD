<?php

namespace App\Models;

use App\Services\AcademicPeriodService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AcademicEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_type',
        'start_date',
        'end_date',
        'semester_id',
        'color',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /* ─── Relations ─── */

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* ─── Scopes ─── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeCurrentlyActive($query, ?Carbon $date = null)
    {
        $date = $date ?? Carbon::now();
        return $query->active()
            ->where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'));
    }

    /* ─── Accessors ─── */

    /**
     * Human-readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return AcademicPeriodService::TYPE_LABELS[$this->event_type] ?? $this->event_type;
    }

    /**
     * Check if this event is currently active (date-wise).
     */
    public function getIsCurrentlyActiveAttribute(): bool
    {
        $now = Carbon::now();
        return $this->is_active
            && $now->between(
                Carbon::parse($this->start_date)->startOfDay(),
                Carbon::parse($this->end_date)->endOfDay()
            );
    }

    /**
     * Days remaining until end_date.
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, (int) Carbon::now()->diffInDays(
            Carbon::parse($this->end_date)->endOfDay(),
            false
        ));
    }
}
