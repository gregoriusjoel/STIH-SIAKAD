<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeachingAssignmentService
{
    /**
     * List current (active semester) assignments for a dosen.
     */
    public function listCurrentAssignments(Dosen $dosen): Collection
    {
        $semester = $this->getActiveSemester();
        if (!$semester) return collect();

        return $this->getAssignments($dosen, $semester->id);
    }

    /**
     * List assignments for a dosen in a specific semester.
     */
    public function getAssignments(Dosen $dosen, int $semesterId): Collection
    {
        return $dosen->mataKuliahs()
            ->wherePivot('semester_id', $semesterId)
            ->orderBy('nama_mk')
            ->get();
    }

    /**
     * List all semesters where a dosen had assignments (for history).
     */
    public function listHistorySemesters(Dosen $dosen): Collection
    {
        $activeSemester = $this->getActiveSemester();
        $activeSemesterId = $activeSemester ? $activeSemester->id : 0;

        return Semester::whereHas('kelasMataKuliahs', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })
            ->orWhereIn('id', function ($q) use ($dosen) {
                $q->select('semester_id')
                    ->from('dosen_mata_kuliah')
                    ->where('dosen_id', $dosen->id);
            })
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('nama_semester')
            ->get()
            ->map(function ($sem) use ($dosen, $activeSemesterId) {
                $sem->is_current = $sem->id === $activeSemesterId;
                $sem->assignment_count = DB::table('dosen_mata_kuliah')
                    ->where('dosen_id', $dosen->id)
                    ->where('semester_id', $sem->id)
                    ->count();
                $sem->kelas_count = $dosen->kelasMataKuliahs()
                    ->where('semester_id', $sem->id)
                    ->count();
                return $sem;
            });
    }

    /**
     * Get history assignments for a specific semester with class info.
     */
    public function getHistoryAssignments(Dosen $dosen, int $semesterId): Collection
    {
        $assigned = $dosen->mataKuliahs()
            ->wherePivot('semester_id', $semesterId)
            ->get();

        // Enrich with kelas info
        $kelasData = $dosen->kelasMataKuliahs()
            ->where('semester_id', $semesterId)
            ->with('mataKuliah')
            ->get()
            ->groupBy('mata_kuliah_id');

        return $assigned->map(function ($mk) use ($kelasData) {
            $mk->kelas_list = $kelasData->get($mk->id, collect());
            return $mk;
        });
    }

    /**
     * Assign subjects to a dosen for a given semester.
     * Uses REPLACE strategy: removes old, inserts new.
     *
     * @param Dosen $dosen
     * @param array $mataKuliahIds Array of mata_kuliah IDs
     * @param int $semesterId
     * @return array ['added' => int, 'removed' => int, 'duplicates' => array]
     */
    public function assignSubjects(Dosen $dosen, array $mataKuliahIds, int $semesterId): array
    {
        $mataKuliahIds = array_map('intval', array_filter($mataKuliahIds));
        $mataKuliahIds = array_unique($mataKuliahIds);

        // Validate MK IDs exist
        $validIds = MataKuliah::whereIn('id', $mataKuliahIds)->pluck('id')->toArray();

        // Check for duplicates (MK already assigned to another dosen in same semester)
        $duplicates = $this->validateNoDuplicate($dosen, $validIds, $semesterId);

        // Filter out duplicates
        $safeIds = array_diff($validIds, array_column($duplicates, 'mata_kuliah_id'));

        $userId = Auth::id();

        DB::transaction(function () use ($dosen, $safeIds, $semesterId, $userId) {
            // Remove existing assignments for this dosen+semester
            DB::table('dosen_mata_kuliah')
                ->where('dosen_id', $dosen->id)
                ->where('semester_id', $semesterId)
                ->delete();

            // Insert new assignments
            $rows = [];
            $now = now();
            foreach ($safeIds as $mkId) {
                $rows[] = [
                    'dosen_id' => $dosen->id,
                    'mata_kuliah_id' => $mkId,
                    'semester_id' => $semesterId,
                    'created_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (!empty($rows)) {
                DB::table('dosen_mata_kuliah')->insert($rows);
            }

            // Also sync dosens.mata_kuliah_ids JSON for backward compatibility
            $allMkIds = DB::table('dosen_mata_kuliah')
                ->where('dosen_id', $dosen->id)
                ->pluck('mata_kuliah_id')
                ->unique()
                ->values()
                ->toArray();
            $dosen->update(['mata_kuliah_ids' => $allMkIds]);
        });

        return [
            'added' => count($safeIds),
            'removed' => 0,
            'duplicates' => $duplicates,
        ];
    }

    /**
     * Remove a single assignment.
     */
    public function removeAssignment(Dosen $dosen, int $mataKuliahId, int $semesterId): bool
    {
        $deleted = DB::table('dosen_mata_kuliah')
            ->where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->where('semester_id', $semesterId)
            ->delete();

        if ($deleted) {
            // Sync backward compat JSON
            $allMkIds = DB::table('dosen_mata_kuliah')
                ->where('dosen_id', $dosen->id)
                ->pluck('mata_kuliah_id')
                ->unique()
                ->values()
                ->toArray();
            $dosen->update(['mata_kuliah_ids' => $allMkIds]);
        }

        return $deleted > 0;
    }

    /**
     * Copy assignments from a previous semester to a target semester.
     *
     * @return array ['copied' => int, 'skipped_duplicates' => array]
     */
    public function copyFromPreviousTA(Dosen $dosen, int $sourceSemesterId, int $targetSemesterId): array
    {
        $sourceMkIds = DB::table('dosen_mata_kuliah')
            ->where('dosen_id', $dosen->id)
            ->where('semester_id', $sourceSemesterId)
            ->pluck('mata_kuliah_id')
            ->toArray();

        if (empty($sourceMkIds)) {
            return ['copied' => 0, 'skipped_duplicates' => []];
        }

        // Check existing in target
        $existingInTarget = DB::table('dosen_mata_kuliah')
            ->where('dosen_id', $dosen->id)
            ->where('semester_id', $targetSemesterId)
            ->pluck('mata_kuliah_id')
            ->toArray();

        $newMkIds = array_diff($sourceMkIds, $existingInTarget);

        // Check cross-dosen duplicates
        $duplicates = $this->validateNoDuplicate($dosen, $newMkIds, $targetSemesterId);
        $safeIds = array_diff($newMkIds, array_column($duplicates, 'mata_kuliah_id'));

        $userId = Auth::id();
        $now = now();
        $rows = [];
        foreach ($safeIds as $mkId) {
            $rows[] = [
                'dosen_id' => $dosen->id,
                'mata_kuliah_id' => $mkId,
                'semester_id' => $targetSemesterId,
                'created_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($rows)) {
            DB::table('dosen_mata_kuliah')->insert($rows);

            // Sync backward compat JSON
            $allMkIds = DB::table('dosen_mata_kuliah')
                ->where('dosen_id', $dosen->id)
                ->pluck('mata_kuliah_id')
                ->unique()
                ->values()
                ->toArray();
            $dosen->update(['mata_kuliah_ids' => $allMkIds]);
        }

        return [
            'copied' => count($safeIds),
            'skipped_duplicates' => $duplicates,
        ];
    }

    /**
     * Validate no duplicate: check if any of the MK IDs are already assigned
     * to ANOTHER dosen in the same semester.
     *
     * @return array Array of ['mata_kuliah_id' => int, 'mata_kuliah_nama' => string, 'dosen_nama' => string]
     */
    public function validateNoDuplicate(Dosen $dosen, array $mataKuliahIds, int $semesterId): array
    {
        // 1 mata kuliah bisa diajar oleh lebih dari 1 dosen,
        // sehingga tidak perlu ada validasi duplikat lintas dosen.
        return [];
    }

    /**
     * Get all available mata kuliah for assignment (optionally filtered by prodi).
     */
    public function getAvailableMataKuliah(?int $semesterId = null): Collection
    {
        $query = MataKuliah::query()->orderBy('kode_mk');

        // If semester provided, prefer MK active in that semester
        if ($semesterId) {
            $query->with(['mataKuliahSemesters' => function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId);
            }]);
        }

        return $query->get();
    }

    /**
     * Get all semesters that have assignments for any dosen (for dropdown).
     */
    public function getSemestersWithAssignments(): Collection
    {
        return Semester::orderByDesc('tahun_ajaran')
            ->orderByDesc('nama_semester')
            ->get();
    }

    /**
     * Get the active semester.
     */
    public function getActiveSemester(): ?Semester
    {
        return Semester::where('is_active', true)->first();
    }

    /**
     * Get the previous semester relative to the given one.
     */
    public function getPreviousSemester(Semester $current): ?Semester
    {
        return Semester::where('id', '<', $current->id)
            ->orderByDesc('id')
            ->first();
    }
}
