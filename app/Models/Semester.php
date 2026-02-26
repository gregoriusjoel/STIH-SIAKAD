<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = [
        'nama_semester',
        'tahun_ajaran',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'krs_dapat_diisi',
        'krs_mulai',
        'krs_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'krs_dapat_diisi' => 'boolean',
        'krs_mulai' => 'date',
        'krs_selesai' => 'date',
    ];

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    /**
     * Get mahasiswa who were updated in this semester
     */
    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'last_semester_id');
    }

    /**
     * Scope to get only active semester
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get upcoming semesters
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>', now())
            ->orderBy('tanggal_mulai', 'asc');
    }

    /**
     * Scope to get semesters that should show active classes
     * (active semester + semesters within grace period)
     */
    public function scopeShowingActiveClasses($query)
    {
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemesterIds = $semesterService->getActiveSemesterIds();
        
        return $query->whereIn('id', $activeSemesterIds);
    }

    /**
     * Check if semester has ended
     */
    public function hasEnded(): bool
    {
        return $this->tanggal_selesai < now();
    }

    /**
     * Check if semester is within grace period
     */
    public function isInGracePeriod(): bool
    {
        $semesterService = app(\App\Services\SemesterService::class);
        return $semesterService->isInGracePeriod($this);
    }

    /**
     * Check if classes from this semester should be visible
     */
    public function shouldShowClasses(): bool
    {
        $semesterService = app(\App\Services\SemesterService::class);
        return in_array($this->id, $semesterService->getActiveSemesterIds());
    }

    /**
     * Get days remaining until grace period ends
     * Returns negative if grace period has ended
     */
    public function getDaysUntilGracePeriodEnds(): int
    {
        if (!$this->tanggal_selesai) {
            return 0;
        }

        $gracePeriodEnd = \Carbon\Carbon::parse($this->tanggal_selesai)
            ->addDays(\App\Services\SemesterService::GRACE_PERIOD_DAYS);
        
        return now()->diffInDays($gracePeriodEnd, false);
    }

    /**
     * Check if KRS period is active
     */
    public function isKRSPeriodActive(): bool
    {
        if (!$this->krs_dapat_diisi) {
            return false;
        }

        $now = now();
        return $now >= $this->krs_mulai && $now <= $this->krs_selesai;
    }
}
