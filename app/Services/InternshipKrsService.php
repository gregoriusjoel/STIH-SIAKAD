<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\InternshipCourseMapping;
use App\Models\Krs;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handles injection of "paket konversi magang" into KRS.
 *
 * Logic:
 * - The campus defines a set of MK marked as jenis='konversi_magang' (or a manual mapping in internship_course_mappings).
 * - When internship goes ONGOING, this service auto-creates KRS entries for those MK.
 * - Each KRS entry is flagged: internship_id = X, is_internship_conversion = true.
 * - Max 16 SKS enforced.
 */
class InternshipKrsService
{
    const MAX_CONVERSION_SKS = 16;

    /**
     * Inject conversion KRS entries for the given internship.
     * Uses the course mappings defined by admin; if none, tries to auto-map from DB.
     */
    public function injectConversionCourses(Internship $internship): void
    {
        $mappings = $internship->courseMappings()->with('mataKuliah')->get();

        if ($mappings->isEmpty()) {
            Log::info("InternshipKrsService: No course mappings for internship #{$internship->id}. Skipping KRS injection.");
            return;
        }

        $totalSks = $mappings->sum('sks');
        if ($totalSks > self::MAX_CONVERSION_SKS) {
            throw new \LogicException("Total SKS konversi ({$totalSks}) melebihi batas maksimal " . self::MAX_CONVERSION_SKS . " SKS.");
        }

        $activeSemester = Semester::where('is_active', true)->first();
        if (!$activeSemester) {
            throw new \RuntimeException('Tidak ada semester aktif.');
        }

        DB::transaction(function () use ($internship, $mappings, $activeSemester) {
            foreach ($mappings as $mapping) {
                // Check if KRS entry already exists for this MK + mahasiswa + semester
                $exists = Krs::where('mahasiswa_id', $internship->mahasiswa_id)
                    ->where('mata_kuliah_id', $mapping->mata_kuliah_id)
                    ->where('internship_id', $internship->id)
                    ->exists();

                if ($exists) continue;

                // Find or skip: if there's a KelasMataKuliah for this MK in active semester, use it.
                // Otherwise, create KRS without kelas_mata_kuliah_id (direct MK reference).
                $kelasMk = KelasMataKuliah::where('mata_kuliah_id', $mapping->mata_kuliah_id)
                    ->where('semester_id', $activeSemester->id)
                    ->first();

                Krs::create([
                    'mahasiswa_id'             => $internship->mahasiswa_id,
                    'mata_kuliah_id'           => $mapping->mata_kuliah_id,
                    'kelas_mata_kuliah_id'     => $kelasMk?->id,
                    'kelas_id'                 => null,
                    'semester_id'              => $activeSemester->id,   // ← FIX: direct semester link untuk KHS grouping
                    'status'                   => 'approved',
                    'keterangan'               => 'Konversi Magang - ' . $internship->instansi,
                    'internship_id'            => $internship->id,
                    'is_internship_conversion' => true,
                ]);
            }
        });

        Log::info("InternshipKrsService: Injected {$mappings->count()} KRS konversi for internship #{$internship->id}.");
    }

    /**
     * Remove conversion KRS entries (e.g., if internship is cancelled).
     */
    public function removeConversionCourses(Internship $internship): void
    {
        Krs::where('internship_id', $internship->id)
            ->where('is_internship_conversion', true)
            ->delete();
    }

    /**
     * Validate total SKS for a mahasiswa in a semester:
     * konversi + normal must not exceed configurable limit.
     */
    public function validateSemesterSks(int $mahasiswaId, int $semesterId, int $maxTotal = 24): array
    {
        $conversionSks = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('is_internship_conversion', true)
            ->whereHas('kelasMataKuliah', fn($q) => $q->where('semester_id', $semesterId))
            ->with('kelasMataKuliah.mataKuliah')
            ->get()
            ->sum(fn($k) => $k->kelasMataKuliah?->mataKuliah?->sks ?? 0);

        $normalSks = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('is_internship_conversion', false)
            ->whereHas('kelasMataKuliah', fn($q) => $q->where('semester_id', $semesterId))
            ->with('kelasMataKuliah.mataKuliah')
            ->get()
            ->sum(fn($k) => $k->kelasMataKuliah?->mataKuliah?->sks ?? 0);

        return [
            'conversion_sks' => $conversionSks,
            'normal_sks'     => $normalSks,
            'total_sks'      => $conversionSks + $normalSks,
            'max_total'      => $maxTotal,
            'is_valid'       => ($conversionSks + $normalSks) <= $maxTotal,
            'conversion_max' => self::MAX_CONVERSION_SKS,
        ];
    }
}
