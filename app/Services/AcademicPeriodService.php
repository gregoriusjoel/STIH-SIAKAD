<?php

namespace App\Services;

use App\Models\AcademicEvent;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Single Source of Truth for academic calendar periods.
 *
 * Reads exclusively from `academic_events` table – no hardcoded dates.
 * Every module (KRS, UTS, UAS, Absensi, Dashboard) must call this service
 * to determine whether a period is currently active.
 *
 * Cache keys:
 *   academic_periods:{semesterId}             → all periods for semester
 *   academic_period_active:{semesterId}:{type} → boolean active flag
 *   academic_period_current_types:{semesterId} → array of currently active types
 */
class AcademicPeriodService
{
    /* ─────────────────────────────────────────────
     |  Constants – standardised type keys
     |  Must match the ENUM in academic_events.event_type
     ───────────────────────────────────────────── */
    const TYPE_PERKULIAHAN   = 'perkuliahan';
    const TYPE_PERIODE_KRS   = 'krs';
    const TYPE_KRS_PERUBAHAN = 'krs_perubahan';
    const TYPE_UTS           = 'uts';
    const TYPE_UAS           = 'uas';
    const TYPE_LIBUR         = 'libur_akademik';
    const TYPE_LAINNYA       = 'lainnya';

    /**
     * Human-readable labels for each type.
     */
    const TYPE_LABELS = [
        self::TYPE_PERKULIAHAN   => 'Perkuliahan',
        self::TYPE_PERIODE_KRS   => 'Periode KRS',
        self::TYPE_KRS_PERUBAHAN => 'KRS Perubahan',
        self::TYPE_UTS           => 'Ujian Tengah Semester',
        self::TYPE_UAS           => 'Ujian Akhir Semester',
        self::TYPE_LIBUR         => 'Libur Akademik',
        self::TYPE_LAINNYA       => 'Lainnya',
    ];

    /**
     * Icon mapping (FontAwesome) for dashboard badges.
     */
    const TYPE_ICONS = [
        self::TYPE_PERKULIAHAN   => 'fas fa-chalkboard-teacher',
        self::TYPE_PERIODE_KRS   => 'fas fa-file-alt',
        self::TYPE_KRS_PERUBAHAN => 'fas fa-exchange-alt',
        self::TYPE_UTS           => 'fas fa-edit',
        self::TYPE_UAS           => 'fas fa-graduation-cap',
        self::TYPE_LIBUR         => 'fas fa-umbrella-beach',
        self::TYPE_LAINNYA       => 'fas fa-calendar',
    ];

    /**
     * Color mapping (Tailwind classes) for badges.
     */
    const TYPE_COLORS = [
        self::TYPE_PERKULIAHAN   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'border' => 'border-blue-300'],
        self::TYPE_PERIODE_KRS   => ['bg' => 'bg-emerald-100','text' => 'text-emerald-800','border' => 'border-emerald-300'],
        self::TYPE_KRS_PERUBAHAN => ['bg' => 'bg-teal-100',   'text' => 'text-teal-800',   'border' => 'border-teal-300'],
        self::TYPE_UTS           => ['bg' => 'bg-amber-100',  'text' => 'text-amber-800',  'border' => 'border-amber-300'],
        self::TYPE_UAS           => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-300'],
        self::TYPE_LIBUR         => ['bg' => 'bg-red-100',    'text' => 'text-red-800',    'border' => 'border-red-300'],
        self::TYPE_LAINNYA       => ['bg' => 'bg-gray-100',   'text' => 'text-gray-800',   'border' => 'border-gray-300'],
    ];

    /** Cache TTL in seconds (10 minutes) */
    const CACHE_TTL = 600;

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     |  Core Methods
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

    /**
     * Get the currently active semester.
     * Delegates to SemesterService for consistency.
     */
    public function getActiveSemester(): ?Semester
    {
        return app(SemesterService::class)->getActiveSemester();
    }

    /**
     * Get all academic events for a semester (cached).
     */
    public function getPeriods(int $semesterId): Collection
    {
        return Cache::remember(
            "academic_periods:{$semesterId}",
            self::CACHE_TTL,
            function () use ($semesterId) {
                $semester = Semester::find($semesterId);

                $query = AcademicEvent::active();

                if ($semester) {
                    $start = $semester->tanggal_mulai;
                    $end   = $semester->tanggal_selesai;

                    $query->where(function ($q) use ($semesterId, $start, $end) {
                        $q->where('semester_id', $semesterId)
                          ->orWhere(function ($q2) use ($start, $end) {
                              $q2->whereNull('semester_id')
                                 ->where('start_date', '<=', $end)
                                 ->where('end_date', '>=', $start);
                          });
                    });
                } else {
                    $query->where('semester_id', $semesterId);
                }

                return $query->orderBy('start_date')->get();
            }
        );
    }

    /**
     * Get the first active event of a given type for a semester.
     * Returns null when no matching event exists.
     */
    public function getPeriodByType(string $type, ?int $semesterId = null): ?AcademicEvent
    {
        $semesterId = $semesterId ?? $this->getActiveSemester()?->id;
        if (!$semesterId) return null;

        $periods = $this->getPeriods($semesterId);
        $now = Carbon::now();

        // Prefer currently-active event, fallback to nearest future event of that type
        $active = $periods->first(function (AcademicEvent $e) use ($type, $now) {
            return $e->event_type === $type
                && $now->between(
                    Carbon::parse($e->start_date)->startOfDay(),
                    Carbon::parse($e->end_date)->endOfDay()
                );
        });

        if ($active) return $active;

        // Fallback: any event of that type in this semester (most recent one)
        return $periods->where('event_type', $type)->sortByDesc('start_date')->first();
    }

    /**
     * Check whether a period type is currently active.
     */
    public function isActive(string $type, ?Carbon $date = null, ?int $semesterId = null): bool
    {
        $semesterId = $semesterId ?? $this->getActiveSemester()?->id;
        if (!$semesterId) return false;

        $date = $date ?? Carbon::now();
        $cacheKey = "academic_period_active:{$semesterId}:{$type}:{$date->format('Y-m-d')}";

        return Cache::remember($cacheKey, 60, function () use ($type, $date, $semesterId) {
            $periods = $this->getPeriods($semesterId);

            return $periods->contains(function (AcademicEvent $e) use ($type, $date) {
                return $e->event_type === $type
                    && $date->between(
                        Carbon::parse($e->start_date)->startOfDay(),
                        Carbon::parse($e->end_date)->endOfDay()
                    );
            });
        });
    }

    /**
     * Assert that a period type is currently active, or abort 403.
     */
    public function assertActive(string $type, ?int $semesterId = null): void
    {
        if (!$this->isActive($type, null, $semesterId)) {
            $label = self::TYPE_LABELS[$type] ?? $type;
            abort(403, "Periode {$label} belum dibuka atau sudah ditutup. Silakan cek Kalender Akademik.");
        }
    }

    /**
     * Get all currently active types for a semester.
     * Useful for dashboard badges.
     */
    public function currentActiveTypes(?int $semesterId = null): array
    {
        $semesterId = $semesterId ?? $this->getActiveSemester()?->id;
        if (!$semesterId) return [];

        $cacheKey = "academic_period_current_types:{$semesterId}:" . Carbon::now()->format('Y-m-d');

        return Cache::remember($cacheKey, 120, function () use ($semesterId) {
            $periods = $this->getPeriods($semesterId);
            $now = Carbon::now();

            return $periods
                ->filter(function (AcademicEvent $e) use ($now) {
                    return $now->between(
                        Carbon::parse($e->start_date)->startOfDay(),
                        Carbon::parse($e->end_date)->endOfDay()
                    );
                })
                ->map(function (AcademicEvent $e) use ($now) {
                    $end = Carbon::parse($e->end_date)->endOfDay();
                    return [
                        'type'       => $e->event_type,
                        'label'      => self::TYPE_LABELS[$e->event_type] ?? $e->event_type,
                        'icon'       => self::TYPE_ICONS[$e->event_type] ?? 'fas fa-calendar',
                        'colors'     => self::TYPE_COLORS[$e->event_type] ?? self::TYPE_COLORS[self::TYPE_LAINNYA],
                        'title'      => $e->title,
                        'start_date' => $e->start_date->format('Y-m-d'),
                        'end_date'   => $e->end_date->format('Y-m-d'),
                        'days_left'  => max(0, (int) $now->diffInDays($end, false)),
                    ];
                })
                ->unique('type')
                ->values()
                ->toArray();
        });
    }

    /**
     * Get the date range for a specific period type.
     * Returns ['start' => Carbon, 'end' => Carbon] or null.
     */
    public function getDateRange(string $type, ?int $semesterId = null): ?array
    {
        $event = $this->getPeriodByType($type, $semesterId);
        if (!$event) return null;

        return [
            'start' => Carbon::parse($event->start_date)->startOfDay(),
            'end'   => Carbon::parse($event->end_date)->endOfDay(),
            'event' => $event,
        ];
    }

    /**
     * Get a human-readable status string for a period type.
     */
    public function getStatus(string $type, ?int $semesterId = null): array
    {
        $semesterId = $semesterId ?? $this->getActiveSemester()?->id;
        $event = $this->getPeriodByType($type, $semesterId);

        if (!$event) {
            return [
                'status'  => 'not_set',
                'label'   => 'Belum diatur',
                'message' => 'Periode belum diatur di Kalender Akademik.',
                'badge'   => 'bg-gray-100 text-gray-600',
            ];
        }

        $now   = Carbon::now();
        $start = Carbon::parse($event->start_date)->startOfDay();
        $end   = Carbon::parse($event->end_date)->endOfDay();

        if ($now->lt($start)) {
            $daysUntil = $now->diffInDays($start);
            return [
                'status'     => 'upcoming',
                'label'      => 'Akan Datang',
                'message'    => "Dibuka dalam {$daysUntil} hari ({$start->translatedFormat('d F Y')})",
                'badge'      => 'bg-blue-100 text-blue-700',
                'start_date' => $start->format('Y-m-d'),
                'end_date'   => $end->format('Y-m-d'),
            ];
        }

        if ($now->between($start, $end)) {
            $daysLeft = max(0, (int) $now->diffInDays($end, false));
            return [
                'status'     => 'active',
                'label'      => 'Aktif',
                'message'    => "Berakhir dalam {$daysLeft} hari ({$end->translatedFormat('d F Y')})",
                'badge'      => 'bg-green-100 text-green-700',
                'days_left'  => $daysLeft,
                'start_date' => $start->format('Y-m-d'),
                'end_date'   => $end->format('Y-m-d'),
            ];
        }

        return [
            'status'     => 'closed',
            'label'      => 'Ditutup',
            'message'    => "Berakhir pada {$end->translatedFormat('d F Y')}",
            'badge'      => 'bg-red-100 text-red-700',
            'start_date' => $start->format('Y-m-d'),
            'end_date'   => $end->format('Y-m-d'),
        ];
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     |  Cache Invalidation
     ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

    /**
     * Invalidate all caches for a specific semester.
     * Call this when admin creates / updates / deletes events.
     */
    public function invalidateCache(?int $semesterId = null): void
    {
        if ($semesterId) {
            Cache::forget("academic_periods:{$semesterId}");

            // Forget all type-specific active caches
            $today = Carbon::now()->format('Y-m-d');
            foreach (array_keys(self::TYPE_LABELS) as $type) {
                Cache::forget("academic_period_active:{$semesterId}:{$type}:{$today}");
            }
            Cache::forget("academic_period_current_types:{$semesterId}:{$today}");
        }

        // Also clear for active semester if different
        $activeSemester = $this->getActiveSemester();
        if ($activeSemester && $activeSemester->id !== $semesterId) {
            $this->invalidateCache($activeSemester->id);
        }

        Log::info('AcademicPeriodService: cache invalidated', [
            'semester_id' => $semesterId,
        ]);
    }

    /**
     * Full cache flush for all semesters (e.g., on semester transition).
     */
    public function invalidateAllCaches(): void
    {
        $semesters = Semester::pluck('id');
        foreach ($semesters as $sid) {
            Cache::forget("academic_periods:{$sid}");
            $today = Carbon::now()->format('Y-m-d');
            foreach (array_keys(self::TYPE_LABELS) as $type) {
                Cache::forget("academic_period_active:{$sid}:{$type}:{$today}");
            }
            Cache::forget("academic_period_current_types:{$sid}:{$today}");
        }

        Log::info('AcademicPeriodService: ALL caches invalidated');
    }
}
