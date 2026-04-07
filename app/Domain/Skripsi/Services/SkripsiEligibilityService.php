<?php

namespace App\Domain\Skripsi\Services;

use App\Models\Mahasiswa;
use App\Models\SkripsiSubmission;
use App\Domain\Skripsi\Enums\SkripsiStatus;

/**
 * Handles SKS & business-rule eligibility checks.
 */
class SkripsiEligibilityService
{
    public const MIN_SKS = 120;
    public const MIN_BIMBINGAN = 8;

    public function getTotalSksLulus(Mahasiswa $mahasiswa): int
    {
        return \App\Models\Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->whereHas('nilai', fn($q) => $q->whereNotIn('grade', ['E', '']))
            ->with(['mataKuliah', 'kelasMataKuliah.mataKuliah'])
            ->get()
            ->sum(function ($krs) {
                $mk = $krs->mataKuliah ?? $krs->kelasMataKuliah?->mataKuliah;
                return $mk?->sks ?? 0;
            });
    }

    public function isSkripsiEligible(Mahasiswa $mahasiswa): bool
    {
        return $this->getTotalSksLulus($mahasiswa) >= self::MIN_SKS;
    }

    public function isSidangEligible(SkripsiSubmission $submission): bool
    {
        return !empty($submission->logbook_file_path);
    }

    public function getSummary(Mahasiswa $mahasiswa): array
    {
        $totalSks     = $this->getTotalSksLulus($mahasiswa);
        $isEligible   = $totalSks >= self::MIN_SKS;
        $submission   = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->first();

        return [
            'total_sks'        => $totalSks,
            'min_sks'          => self::MIN_SKS,
            'sks_eligible'     => $isEligible,
            'sks_shortage'     => max(0, self::MIN_SKS - $totalSks),
            'submission'       => $submission,
            'total_bimbingan'  => $submission?->total_bimbingan ?? 0,
            'min_bimbingan'    => self::MIN_BIMBINGAN,
            'has_logbook'      => !empty($submission?->logbook_file_path),
            'logbook_name'     => $submission?->logbook_original_name,
            'logbook_date'     => $submission?->logbook_uploaded_at,
            'sidang_eligible'  => $submission ? $this->isSidangEligible($submission) : false,
        ];
    }
}
