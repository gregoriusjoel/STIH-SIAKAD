<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenAvailability extends Model
{
    protected $fillable = [
        'dosen_id',
        'semester_id',
        'hari',
        'jam_perkuliahan_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship: Belongs to Dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Relationship: Belongs to Semester
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Relationship: Belongs to JamPerkuliahan
     */
    public function jamPerkuliahan(): BelongsTo
    {
        return $this->belongsTo(JamPerkuliahan::class);
    }

    /**
     * Scope: Only available slots
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope: For specific semester
     */
    public function scopeForSemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    /**
     * Scope: For specific dosen
     */
    public function scopeForDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    /**
     * Scope: For specific day
     */
    public function scopeForDay($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Check if slot is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Mark slot as booked
     */
    public function markAsBooked(): void
    {
        $this->update(['status' => 'booked']);
    }

    /**
     * Mark slot as available
     */
    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }
}
