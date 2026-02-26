<?php

namespace App\Services;

use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing semester lifecycle, status, and grace periods
 * 
 * Grace Period Policy:
 * - When a semester ends, classes remain "active" for 14 days grace period
 * - After 14 days, old semester classes no longer appear as active
 * - This allows smooth transition and final activities post-semester
 */
class SemesterService
{
    /**
     * Grace period in days after semester ends
     */
    const GRACE_PERIOD_DAYS = 14;

    /**
     * Cache duration for active semester (5 minutes)
     */
    const CACHE_DURATION = 300;

    /**
     * Get the currently active semester
     * Cached for performance
     */
    public function getActiveSemester(): ?Semester
    {
        return Cache::remember('active_semester', self::CACHE_DURATION, function () {
            return Semester::where('is_active', true)
                ->orWhere('status', 'aktif')
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        });
    }

    /**
     * Get all semester IDs that should show active classes
     * Includes: active semester + semesters within grace period
     * 
     * @return array Array of semester IDs
     */
    public function getActiveSemesterIds(): array
    {
        $cacheKey = 'active_semester_ids';
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            $semesters = collect();

            // 1. Get current active semester
            $activeSemester = $this->getActiveSemester();
            if ($activeSemester) {
                $semesters->push($activeSemester->id);
            }

            // 2. Get semesters within grace period
            $gracePeriodSemesters = $this->getSemestersInGracePeriod();
            $semesters = $semesters->merge($gracePeriodSemesters->pluck('id'));

            return $semesters->unique()->values()->toArray();
        });
    }

    /**
     * Get semesters that have ended but are still within grace period
     * 
     * @return Collection<Semester>
     */
    public function getSemestersInGracePeriod(): Collection
    {
        $now = Carbon::now();
        $gracePeriodStart = $now->copy()->subDays(self::GRACE_PERIOD_DAYS);

        return Semester::where('tanggal_selesai', '<', $now)
            ->where('tanggal_selesai', '>=', $gracePeriodStart)
            ->get();
    }

    /**
     * Check if a semester is currently within grace period
     */
    public function isInGracePeriod(Semester $semester): bool
    {
        if (!$semester->tanggal_selesai) {
            return false;
        }

        $now = Carbon::now();
        $endDate = Carbon::parse($semester->tanggal_selesai);
        
        // Has ended and within 14 days of ending
        return $now->greaterThan($endDate) 
            && $now->diffInDays($endDate, false) >= -self::GRACE_PERIOD_DAYS;
    }

    /**
     * Get semesters that have passed grace period and should be archived
     * 
     * @return Collection<Semester>
     */
    public function getSemestersToArchive(): Collection
    {
        $gracePeriodEnd = Carbon::now()->subDays(self::GRACE_PERIOD_DAYS);

        return Semester::where('is_active', true)
            ->orWhere('status', 'aktif')
            ->where('tanggal_selesai', '<', $gracePeriodEnd)
            ->get();
    }

    /**
     * Check if semester should be automatically activated
     * (Start date has arrived or passed)
     */
    public function shouldActivate(Semester $semester): bool
    {
        if (!$semester->tanggal_mulai) {
            return false;
        }

        $now = Carbon::now();
        $startDate = Carbon::parse($semester->tanggal_mulai);

        return $now->greaterThanOrEqualTo($startDate) 
            && !$semester->is_active 
            && $semester->status !== 'aktif';
    }

    /**
     * Check if semester grace period has ended and should be deactivated
     */
    public function shouldDeactivate(Semester $semester): bool
    {
        if (!$semester->tanggal_selesai) {
            return false;
        }

        $gracePeriodEnd = Carbon::parse($semester->tanggal_selesai)
            ->addDays(self::GRACE_PERIOD_DAYS);

        return Carbon::now()->greaterThan($gracePeriodEnd)
            && ($semester->is_active || $semester->status === 'aktif');
    }

    /**
     * Activate a semester (set as current active semester)
     * Ensures only one semester is active at a time
     */
    public function activateSemester(Semester $semester): bool
    {
        try {
            // Deactivate all other semesters
            Semester::where('id', '!=', $semester->id)
                ->update([
                    'is_active' => false,
                    'status' => 'non-aktif'
                ]);

            // Activate this semester
            $semester->update([
                'is_active' => true,
                'status' => 'aktif'
            ]);

            // Clear cache
            $this->clearCache();

            Log::info("Semester activated: {$semester->nama_semester} {$semester->tahun_ajaran}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to activate semester {$semester->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Deactivate a semester
     * Called after grace period ends
     */
    public function deactivateSemester(Semester $semester): bool
    {
        try {
            $semester->update([
                'is_active' => false,
                'status' => 'non-aktif',
                'krs_dapat_diisi' => false
            ]);

            // Clear cache
            $this->clearCache();

            Log::info("Semester deactivated: {$semester->nama_semester} {$semester->tahun_ajaran} (Grace period ended)");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to deactivate semester {$semester->id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Process automatic semester status updates
     * Called by scheduler daily
     * 
     * @return array Status report
     */
    public function processAutomaticStatusUpdates(): array
    {
        $report = [
            'activated' => [],
            'deactivated' => [],
            'errors' => []
        ];

        try {
            // 1. Check for semesters that should be activated
            $toActivate = Semester::whereNotNull('tanggal_mulai')
                ->where('tanggal_mulai', '<=', Carbon::now())
                ->where('is_active', false)
                ->where('status', '!=', 'aktif')
                ->get();

            foreach ($toActivate as $semester) {
                if ($this->shouldActivate($semester)) {
                    if ($this->activateSemester($semester)) {
                        $report['activated'][] = "{$semester->nama_semester} {$semester->tahun_ajaran}";
                    }
                }
            }

            // 2. Check for semesters that should be deactivated (grace period ended)
            $toDeactivate = $this->getSemestersToArchive();

            foreach ($toDeactivate as $semester) {
                if ($this->shouldDeactivate($semester)) {
                    if ($this->deactivateSemester($semester)) {
                        $report['deactivated'][] = "{$semester->nama_semester} {$semester->tahun_ajaran}";
                    }
                }
            }

        } catch (\Exception $e) {
            $report['errors'][] = $e->getMessage();
            Log::error("Error in automatic semester status updates: {$e->getMessage()}");
        }

        return $report;
    }

    /**
     * Get detailed status for a semester including grace period info
     */
    public function getSemesterStatus(Semester $semester): array
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($semester->tanggal_mulai);
        $endDate = Carbon::parse($semester->tanggal_selesai);
        $gracePeriodEnd = $endDate->copy()->addDays(self::GRACE_PERIOD_DAYS);

        $status = 'upcoming';
        if ($now->greaterThanOrEqualTo($startDate) && $now->lessThanOrEqualTo($endDate)) {
            $status = 'ongoing';
        } elseif ($now->greaterThan($endDate) && $now->lessThanOrEqualTo($gracePeriodEnd)) {
            $status = 'grace_period';
        } elseif ($now->greaterThan($gracePeriodEnd)) {
            $status = 'ended';
        }

        return [
            'id' => $semester->id,
            'nama_semester' => $semester->nama_semester,
            'tahun_ajaran' => $semester->tahun_ajaran,
            'is_active' => $semester->is_active,
            'status' => $status,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'grace_period_end' => $gracePeriodEnd->format('Y-m-d'),
            'days_until_grace_end' => $now->diffInDays($gracePeriodEnd, false),
            'is_in_grace_period' => $this->isInGracePeriod($semester),
            'should_show_classes' => in_array($semester->id, $this->getActiveSemesterIds())
        ];
    }

    /**
     * Clear all semester-related caches
     */
    public function clearCache(): void
    {
        Cache::forget('active_semester');
        Cache::forget('active_semester_ids');
    }

    /**
     * Get human-readable grace period description
     */
    public static function getGracePeriodDescription(): string
    {
        return "Kelas semester lama tetap aktif selama " . self::GRACE_PERIOD_DAYS . " hari setelah semester berakhir untuk masa transisi.";
    }
}
