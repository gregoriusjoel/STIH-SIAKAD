<?php

namespace App\Services;

use App\Models\BobotPenilaian;
use App\Models\Internship;
use App\Models\InternshipCourseMapping;
use App\Models\Krs;
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handles grading of internship conversion courses.
 * Akademik inputs a single internship grade; this service distributes it to the associated KRS/Nilai entries.
 *
 * Grade scale is aligned with Nilai::convertToGrade() — STIH Adhyaksa standard.
 */
class InternshipGradingService
{
    /**
     * Grade map aligned with Nilai::convertToGrade() — STIH Adhyaksa standard.
     * Ordered highest-first.
     */
    const GRADE_MAP = [
        'A'  => ['min' => 80, 'bobot' => 4.00],
        'A-' => ['min' => 76, 'bobot' => 3.67],
        'B+' => ['min' => 72, 'bobot' => 3.33],
        'B'  => ['min' => 68, 'bobot' => 3.00],
        'B-' => ['min' => 64, 'bobot' => 2.67],
        'C+' => ['min' => 60, 'bobot' => 2.33],
        'C'  => ['min' => 56, 'bobot' => 2.00],
        'D'  => ['min' => 45, 'bobot' => 1.00],
        'E'  => ['min' => 0,  'bobot' => 0.00],
    ];

    /**
     * Input grades for all conversion courses of an internship.
     *
     * @param Internship $internship
     * @param array $grades  Keyed by mata_kuliah_id: ['id' => ['nilai_akhir' => 85, 'grade' => 'A']]
     */
    public function inputGrades(Internship $internship, array $grades): void
    {
        if (!in_array($internship->status, [Internship::STATUS_COMPLETED, Internship::STATUS_GRADED])) {
            throw new \LogicException('Nilai hanya bisa diinput saat magang sudah selesai (completed).');
        }

        DB::transaction(function () use ($internship, $grades) {
            $krsEntries = Krs::where('internship_id', $internship->id)
                ->where('is_internship_conversion', true)
                ->get();

            foreach ($krsEntries as $krs) {
                $mkId = $krs->mata_kuliah_id;
                if (!isset($grades[$mkId])) continue;

                $gradeData = $grades[$mkId];
                $nilaiAkhir = (float)($gradeData['nilai_akhir'] ?? 0);
                $grade = $gradeData['grade'] ?? $this->calculateGrade($nilaiAkhir);
                $bobot = self::GRADE_MAP[$grade]['bobot'] ?? 0;

                Nilai::updateOrCreate(
                    ['krs_id' => $krs->id],
                    [
                        'kelas_id'          => $krs->kelas_id,
                        'nilai_akhir'       => $nilaiAkhir,
                        'grade'             => $grade,
                        'bobot'             => $bobot,
                        'is_published'      => true,
                        'published_at'      => now(),
                        // Set all component scores to the same value (internship doesn't have component breakdown)
                        'nilai_partisipatif' => $nilaiAkhir,
                        'nilai_proyek'       => $nilaiAkhir,
                        'nilai_quiz'         => $nilaiAkhir,
                        'nilai_tugas'        => $nilaiAkhir,
                        'nilai_uts'          => $nilaiAkhir,
                        'nilai_uas'          => $nilaiAkhir,
                    ]
                );
            }
        });

        Log::info("InternshipGradingService: Grades input for internship #{$internship->id}.");
    }

    /**
     * Convert numeric score to letter grade + bobot (consistent with GRADE_MAP).
     */
    public function calculateGrade(float $nilai): string
    {
        foreach (self::GRADE_MAP as $grade => $config) {
            if ($nilai >= $config['min']) {
                return $grade;
            }
        }
        return 'E';
    }

    /**
     * Realtime preview: given a nilai_akhir, return grade, bobot, and preview IPS contribution.
     * Used by AJAX endpoint — computed fully client-side safe (no DB hit).
     */
    public function previewGrade(float $nilaiAkhir, int $sks): array
    {
        $grade = $this->calculateGrade($nilaiAkhir);
        $bobot = self::GRADE_MAP[$grade]['bobot'] ?? 0;
        $mutu  = $bobot * $sks;

        return [
            'nilai_akhir' => $nilaiAkhir,
            'grade'       => $grade,
            'bobot'       => $bobot,
            'sks'         => $sks,
            'mutu'        => $mutu,   // bobot × SKS = kontribusi ke IPS
        ];
    }

    /**
     * Preview IPS/IPK contribution for a full set of grade inputs.
     * Used by AJAX endpoint to compute preview without saving.
     *
     * @param array $items  [['nilai_akhir' => 85, 'sks' => 3], ...]
     */
    public function previewIps(array $items): array
    {
        $totalSks   = 0;
        $totalMutu  = 0;
        $details    = [];

        foreach ($items as $item) {
            $nilaiAkhir  = (float)($item['nilai_akhir'] ?? 0);
            $sks         = (int)($item['sks'] ?? 0);
            $preview     = $this->previewGrade($nilaiAkhir, $sks);

            $totalSks   += $sks;
            $totalMutu  += $preview['mutu'];
            $details[]   = $preview;
        }

        $ips = $totalSks > 0 ? round($totalMutu / $totalSks, 2) : 0;

        return [
            'details'    => $details,
            'total_sks'  => $totalSks,
            'total_mutu' => round($totalMutu, 2),
            'ips'        => $ips,
        ];
    }

    /**
     * Get grading summary for an internship.
     */
    public function getGradeSummary(Internship $internship): array
    {
        $mappings = $internship->courseMappings()->with('mataKuliah')->get();
        $krsEntries = Krs::where('internship_id', $internship->id)
            ->where('is_internship_conversion', true)
            ->with('nilai')
            ->get()
            ->keyBy('mata_kuliah_id');

        $summary = [];
        foreach ($mappings as $mapping) {
            $krs = $krsEntries[$mapping->mata_kuliah_id] ?? null;
            $nilai = $krs?->nilai;

            $summary[] = [
                'mata_kuliah_id'   => $mapping->mata_kuliah_id,
                'kode_mk'         => $mapping->mataKuliah?->kode_mk ?? '-',
                'nama_mk'         => $mapping->mataKuliah?->nama_mk ?? '-',
                'sks'             => $mapping->sks,
                'nilai_akhir'     => $nilai?->nilai_akhir,
                'grade'           => $nilai?->grade,
                'bobot'           => $nilai?->bobot,
                'is_published'    => (bool)$nilai?->is_published,
            ];
        }

        return $summary;
    }
}
