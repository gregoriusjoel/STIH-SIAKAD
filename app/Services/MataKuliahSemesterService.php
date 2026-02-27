<?php

namespace App\Services;

use App\Models\MataKuliah;
use App\Models\MataKuliahSemester;
use App\Models\Semester;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MataKuliahSemesterService
{
    protected AuditLogService $auditLog;

    public function __construct(AuditLogService $auditLog)
    {
        $this->auditLog = $auditLog;
    }

    /* ═══════════════════════════════════════════
     *  ATTACH MK TO ACTIVE SEMESTER
     * ═══════════════════════════════════════════ */

    /**
     * Attach mata kuliah IDs to the given semester.
     * Skips duplicates via upsert logic.
     *
     * @return array{attached: int, skipped: int, errors: array}
     */
    public function attachToSemester(int $semesterId, array $mataKuliahIds): array
    {
        $semester = Semester::findOrFail($semesterId);
        $this->guardLocked($semester);

        $result = ['attached' => 0, 'skipped' => 0, 'errors' => []];

        DB::beginTransaction();
        try {
            foreach ($mataKuliahIds as $mkId) {
                $existing = MataKuliahSemester::where('semester_id', $semesterId)
                    ->where('mata_kuliah_id', $mkId)
                    ->first();

                if ($existing) {
                    if ($existing->status === 'active') {
                        $result['skipped']++;
                        continue;
                    }
                    // MK exists in history/archived — set it active again.
                    // Conceptually this is just "adding from master catalog",
                    // not a restore operation.
                    $before = $existing->toArray();
                    $existing->update([
                        'status'       => 'active',
                        'activated_at' => now(),
                        'deactivated_at' => null,
                        'source_semester_id' => null,
                    ]);
                    $result['attached']++;

                    $this->auditLog->log(
                        'add_mk_to_semester',
                        'mata_kuliah_semester',
                        $existing->id,
                        $before,
                        $existing->fresh()->toArray(),
                        ['semester_id' => $semesterId, 'mata_kuliah_id' => $mkId]
                    );
                    continue;
                }

                $pivot = MataKuliahSemester::create([
                    'semester_id' => $semesterId,
                    'mata_kuliah_id' => $mkId,
                    'status' => 'active',
                    'activated_at' => now(),
                ]);

                $result['attached']++;

                $this->auditLog->log(
                    'attach_mk',
                    'mata_kuliah_semester',
                    $pivot->id,
                    null,
                    $pivot->toArray(),
                    ['semester_id' => $semesterId, 'mata_kuliah_id' => $mkId]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("attachToSemester failed: {$e->getMessage()}");
            $result['errors'][] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Attach to the currently active semester
     */
    public function attachToActiveSemester(array $mataKuliahIds): array
    {
        $active = Semester::where('is_active', true)->firstOrFail();
        return $this->attachToSemester($active->id, $mataKuliahIds);
    }

    /* ═══════════════════════════════════════════
     *  DETACH (soft: mark as history)
     * ═══════════════════════════════════════════ */

    /**
     * Detach (soft) mata kuliah from a semester — marks as history
     */
    public function detachFromSemester(int $semesterId, array $mataKuliahIds): int
    {
        $semester = Semester::findOrFail($semesterId);
        $this->guardLocked($semester);

        $count = 0;

        DB::beginTransaction();
        try {
            $pivots = MataKuliahSemester::where('semester_id', $semesterId)
                ->whereIn('mata_kuliah_id', $mataKuliahIds)
                ->where('status', 'active')
                ->get();

            foreach ($pivots as $pivot) {
                $before = $pivot->toArray();
                $pivot->deactivate();
                $count++;

                $this->auditLog->log(
                    'detach_mk',
                    'mata_kuliah_semester',
                    $pivot->id,
                    $before,
                    $pivot->fresh()->toArray()
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("detachFromSemester failed: {$e->getMessage()}");
            throw $e;
        }

        return $count;
    }

    /* ═══════════════════════════════════════════
     *  CARRY FORWARD
     * ═══════════════════════════════════════════ */

    /**
     * Preview what will be carried forward
     *
     * @return array{to_copy: Collection, conflicts: Collection, source_total: int}
     */
    public function previewCarryForward(int $sourceSemesterId, int $targetSemesterId): array
    {
        $sourceMkIds = MataKuliahSemester::where('semester_id', $sourceSemesterId)
            ->whereIn('status', ['active', 'history'])
            ->pluck('mata_kuliah_id');

        $existingInTarget = MataKuliahSemester::where('semester_id', $targetSemesterId)
            ->pluck('mata_kuliah_id');

        $toCopyIds = $sourceMkIds->diff($existingInTarget);
        $conflictIds = $sourceMkIds->intersect($existingInTarget);

        $toCopy = MataKuliah::with('prodi')
            ->whereIn('id', $toCopyIds)
            ->get();

        $conflicts = MataKuliah::with('prodi')
            ->whereIn('id', $conflictIds)
            ->get();

        return [
            'to_copy' => $toCopy,
            'conflicts' => $conflicts,
            'source_total' => $sourceMkIds->count(),
        ];
    }

    /**
     * Execute carry forward from source semester to target semester
     *
     * @return array{copied: int, skipped: int, errors: array}
     */
    public function carryForward(int $sourceSemesterId, int $targetSemesterId): array
    {
        $targetSemester = Semester::findOrFail($targetSemesterId);
        $this->guardLocked($targetSemester);

        $result = ['copied' => 0, 'skipped' => 0, 'errors' => []];

        DB::beginTransaction();
        try {
            $sourcePivots = MataKuliahSemester::where('semester_id', $sourceSemesterId)
                ->whereIn('status', ['active', 'history'])
                ->get();

            foreach ($sourcePivots as $sourcePivot) {
                $existing = MataKuliahSemester::where('semester_id', $targetSemesterId)
                    ->where('mata_kuliah_id', $sourcePivot->mata_kuliah_id)
                    ->first();

                if ($existing) {
                    $result['skipped']++;
                    continue;
                }

                $newPivot = MataKuliahSemester::create([
                    'semester_id' => $targetSemesterId,
                    'mata_kuliah_id' => $sourcePivot->mata_kuliah_id,
                    'status' => 'active',
                    'source_semester_id' => $sourceSemesterId,
                    'activated_at' => now(),
                    'meta' => [
                        'carried_from' => $sourceSemesterId,
                        'original_status' => $sourcePivot->status,
                    ],
                ]);

                $result['copied']++;

                $this->auditLog->log(
                    'carry_forward',
                    'mata_kuliah_semester',
                    $newPivot->id,
                    null,
                    $newPivot->toArray(),
                    [
                        'source_semester_id' => $sourceSemesterId,
                        'target_semester_id' => $targetSemesterId,
                        'mata_kuliah_id' => $sourcePivot->mata_kuliah_id,
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("carryForward failed: {$e->getMessage()}");
            $result['errors'][] = $e->getMessage();
        }

        return $result;
    }

    /* ═══════════════════════════════════════════
     *  RESTORE FROM HISTORY
     * ═══════════════════════════════════════════ */

    /**
     * Restore selected MK from a source semester into the target semester
     *
     * @return array{restored: int, skipped: int, errors: array}
     */
    public function restoreFromSemester(int $sourceSemesterId, int $targetSemesterId, array $mataKuliahIds): array
    {
        $targetSemester = Semester::findOrFail($targetSemesterId);
        $this->guardLocked($targetSemester);

        $result = ['restored' => 0, 'skipped' => 0, 'errors' => []];

        DB::beginTransaction();
        try {
            foreach ($mataKuliahIds as $mkId) {
                $existing = MataKuliahSemester::where('semester_id', $targetSemesterId)
                    ->where('mata_kuliah_id', $mkId)
                    ->first();

                if ($existing && $existing->status === 'active') {
                    $result['skipped']++;
                    continue;
                }

                if ($existing) {
                    $before = $existing->toArray();
                    $existing->update([
                        'status' => 'active',
                        'source_semester_id' => $sourceSemesterId,
                        'activated_at' => now(),
                        'deactivated_at' => null,
                    ]);
                    $result['restored']++;

                    $this->auditLog->log(
                        'restore_mk',
                        'mata_kuliah_semester',
                        $existing->id,
                        $before,
                        $existing->fresh()->toArray(),
                        ['source_semester_id' => $sourceSemesterId]
                    );
                    continue;
                }

                $pivot = MataKuliahSemester::create([
                    'semester_id' => $targetSemesterId,
                    'mata_kuliah_id' => $mkId,
                    'status' => 'active',
                    'source_semester_id' => $sourceSemesterId,
                    'activated_at' => now(),
                    'meta' => ['restored_from' => $sourceSemesterId],
                ]);

                $result['restored']++;

                $this->auditLog->log(
                    'restore_mk',
                    'mata_kuliah_semester',
                    $pivot->id,
                    null,
                    $pivot->toArray(),
                    ['source_semester_id' => $sourceSemesterId]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("restoreFromSemester failed: {$e->getMessage()}");
            $result['errors'][] = $e->getMessage();
        }

        return $result;
    }

    /* ═══════════════════════════════════════════
     *  QUERIES
     * ═══════════════════════════════════════════ */

    /**
     * Get active MK for a semester with eager-loaded relations (no N+1)
     */
    public function getActiveMKBySemester(int $semesterId)
    {
        return MataKuliahSemester::with(['mataKuliah.prodi', 'mataKuliah.fakultas', 'sourceSemester'])
            ->where('semester_id', $semesterId)
            ->where('status', 'active')
            ->orderBy('activated_at', 'desc')
            ->get();
    }

    /**
     * Get history/archived MK for a semester
     */
    public function getHistoryMKBySemester(int $semesterId)
    {
        return MataKuliahSemester::with(['mataKuliah.prodi', 'mataKuliah.fakultas', 'sourceSemester'])
            ->where('semester_id', $semesterId)
            ->whereIn('status', ['history', 'archived'])
            ->orderBy('deactivated_at', 'desc')
            ->get();
    }

    /**
     * Get MK not yet attached to a semester (for the attach modal)
     */
    public function getUnattachedMK(int $semesterId)
    {
        // Only exclude MK that are ACTIVE in this semester.
        // History/archived pivots can be re-activated via attachToSemester.
        $activeIds = MataKuliahSemester::where('semester_id', $semesterId)
            ->where('status', 'active')
            ->pluck('mata_kuliah_id');

        return MataKuliah::with('prodi')
            ->whereNotIn('id', $activeIds)
            ->orderBy('nama_mk')
            ->get();
    }

    /* ═══════════════════════════════════════════
     *  SEMESTER TRANSITION HELPERS
     * ═══════════════════════════════════════════ */

    /**
     * Deactivate all MK pivots for a semester (when transitioning away)
     */
    public function deactivateAllForSemester(int $semesterId): int
    {
        $count = 0;

        $pivots = MataKuliahSemester::where('semester_id', $semesterId)
            ->where('status', 'active')
            ->get();

        foreach ($pivots as $pivot) {
            $pivot->deactivate();
            $count++;
        }

        return $count;
    }

    /* ═══════════════════════════════════════════
     *  GUARD
     * ═══════════════════════════════════════════ */

    /**
     * Throw exception if semester is locked (unless superadmin)
     */
    protected function guardLocked(Semester $semester): void
    {
        if ($semester->isLocked()) {
            // Allow superadmin to bypass
            $user = auth()->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('superadmin')) {
                return;
            }

            throw new \App\Exceptions\SemesterLockedException(
                "Semester \"{$semester->display_label}\" sudah dikunci. Perubahan tidak diizinkan."
            );
        }
    }
}
