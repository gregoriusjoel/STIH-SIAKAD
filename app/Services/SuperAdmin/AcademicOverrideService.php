<?php

namespace App\Services\SuperAdmin;

use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

/**
 * AcademicOverrideService
 * Handles all grade/KRS overrides with proper side-effect management.
 * IPK/IPS is computed on-the-fly from krs→nilai, no stored column to update.
 */
class AcademicOverrideService
{
    /**
     * Override a student's grade (nilai_akhir, grade, bobot).
     * Automatically sets is_published = true after override.
     *
     * @param  Nilai   $nilai
     * @param  float   $newNilaiAkhir
     * @param  string  $reason
     * @return array   ['before' => [...], 'after' => [...], 'ipk_estimate' => float]
     */
    public function overrideGrade(Nilai $nilai, float $newNilaiAkhir, string $reason): array
    {
        $before = $nilai->only(['nilai_akhir', 'grade', 'bobot', 'is_published']);
        $gradeData = Nilai::convertToGrade($newNilaiAkhir);

        DB::transaction(function () use ($nilai, $newNilaiAkhir, $gradeData, $reason, $before) {
            $nilai->update([
                'nilai_akhir'  => $newNilaiAkhir,
                'grade'        => $gradeData['grade'],
                'bobot'        => $gradeData['bobot'],
                'is_published' => true,
                'published_at' => $nilai->published_at ?? now(),
            ]);

            AuditLog::log(
                action: 'nilai.override',
                auditable: $nilai,
                meta: [
                    'reason'       => $reason,
                    'mahasiswa_id' => $nilai->krs?->mahasiswa_id,
                    'krs_id'       => $nilai->krs_id,
                    'mata_kuliah'  => $nilai->krs?->mataKuliah?->nama_mk,
                ],
                before: $before,
                after: [
                    'nilai_akhir' => $newNilaiAkhir,
                    'grade'       => $gradeData['grade'],
                    'bobot'       => $gradeData['bobot'],
                ]
            );
        });

        // Estimate new IPK for the student after override
        $mahasiswaId = $nilai->krs?->mahasiswa_id;
        $ipkEstimate = $mahasiswaId ? $this->calculateIpk($mahasiswaId) : null;

        return [
            'before'       => $before,
            'after'        => array_merge(['nilai_akhir' => $newNilaiAkhir], $gradeData),
            'ipk_estimate' => $ipkEstimate,
        ];
    }

    /**
     * Override KRS status (draft/sudah submit/approved/rejected).
     */
    public function overrideKrsStatus(Krs $krs, string $newStatus, string $reason): void
    {
        $before = $krs->only(['status', 'keterangan']);

        DB::transaction(function () use ($krs, $newStatus, $reason, $before) {
            $krs->update([
                'status'     => $newStatus,
                'keterangan' => $reason,
            ]);

            AuditLog::log(
                action: 'krs.override',
                auditable: $krs,
                meta: [
                    'reason'       => $reason,
                    'mahasiswa_id' => $krs->mahasiswa_id,
                    'mata_kuliah'  => $krs->mataKuliah?->nama_mk,
                ],
                before: $before,
                after: ['status' => $newStatus]
            );
        });
    }

    /**
     * Calculate IPK (Cumulative GPA) for a student.
     * Formula: Σ(bobot × sks) / Σ(sks) for all approved KRS with published nilai.
     */
    public function calculateIpk(int $mahasiswaId): float
    {
        $rows = DB::select("
            SELECT n.bobot, mk.sks
            FROM krs k
            INNER JOIN nilai n ON n.krs_id = k.id
            INNER JOIN mata_kuliahs mk ON mk.id = k.mata_kuliah_id
            WHERE k.mahasiswa_id = ?
              AND k.status = 'approved'
              AND k.ambil_mk = 'ya'
              AND n.is_published = 1
              AND n.bobot IS NOT NULL
              AND n.grade != 'E'
        ", [$mahasiswaId]);

        if (empty($rows)) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($rows as $row) {
            $totalBobot += (float) $row->bobot * (int) $row->sks;
            $totalSks   += (int) $row->sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    /**
     * Calculate IPS (Semester GPA) for a specific semester/tahun_ajaran.
     */
    public function calculateIps(int $mahasiswaId, string $tahunAjaran): float
    {
        $rows = DB::select("
            SELECT n.bobot, mk.sks
            FROM krs k
            INNER JOIN nilai n ON n.krs_id = k.id
            INNER JOIN mata_kuliahs mk ON mk.id = k.mata_kuliah_id
            WHERE k.mahasiswa_id = ?
              AND k.tahun_ajaran = ?
              AND k.status = 'approved'
              AND k.ambil_mk = 'ya'
              AND n.is_published = 1
              AND n.bobot IS NOT NULL
        ", [$mahasiswaId, $tahunAjaran]);

        if (empty($rows)) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($rows as $row) {
            $totalBobot += (float) $row->bobot * (int) $row->sks;
            $totalSks   += (int) $row->sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    /**
     * Get full transcript summary: per-semester IPS and cumulative IPK.
     */
    public function getTranscriptSummary(int $mahasiswaId): array
    {
        $rows = DB::select("
            SELECT k.tahun_ajaran, n.bobot, mk.sks, mk.nama_mk, n.grade, n.nilai_akhir
            FROM krs k
            INNER JOIN nilai n ON n.krs_id = k.id
            INNER JOIN mata_kuliahs mk ON mk.id = k.mata_kuliah_id
            WHERE k.mahasiswa_id = ?
              AND k.status = 'approved'
              AND k.ambil_mk = 'ya'
              AND n.is_published = 1
            ORDER BY k.tahun_ajaran ASC
        ", [$mahasiswaId]);

        $semesters = [];
        foreach ($rows as $row) {
            $ta = $row->tahun_ajaran ?? 'Unknown';
            if (!isset($semesters[$ta])) {
                $semesters[$ta] = ['total_bobot' => 0, 'total_sks' => 0, 'courses' => []];
            }
            $semesters[$ta]['total_bobot'] += (float) $row->bobot * (int) $row->sks;
            $semesters[$ta]['total_sks']   += (int) $row->sks;
            $semesters[$ta]['courses'][]    = $row;
        }

        $result = [];
        $cumBobot = 0;
        $cumSks   = 0;
        foreach ($semesters as $ta => $data) {
            $ips = $data['total_sks'] > 0 ? round($data['total_bobot'] / $data['total_sks'], 2) : 0;
            $cumBobot += $data['total_bobot'];
            $cumSks   += $data['total_sks'];
            $result[$ta] = [
                'tahun_ajaran' => $ta,
                'ips'          => $ips,
                'ipk_sd_sini'  => $cumSks > 0 ? round($cumBobot / $cumSks, 2) : 0,
                'total_sks'    => $data['total_sks'],
                'courses'      => $data['courses'],
            ];
        }

        return $result;
    }
}
