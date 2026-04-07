<?php

namespace App\Domain\Thesis\Services;

use App\Models\Mahasiswa;
use App\Models\ThesisSubmission;
use App\Domain\Thesis\Enums\ThesisStatus;

/**
 * Handles SKS & business-rule eligibility checks.
 */
class ThesisEligibilityService
{
    public const MIN_SKS = 120;
    public const MIN_BIMBINGAN = 8;

    /**
     * Total SKS lulus (grade != E/D, bisa disesuaikan) dari seluruh KRS mahasiswa.
     */
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

    /**
     * Check apakah mahasiswa memenuhi syarat minimal SKS untuk modul skripsi.
     */
    public function isSkripsiEligible(Mahasiswa $mahasiswa): bool
    {
        return $this->getTotalSksLulus($mahasiswa) >= self::MIN_SKS;
    }

    /**
     * Check apakah mahasiswa sudah memenuhi syarat untuk daftar sidang.
     * Sekarang berdasarkan upload logbook PDF (bukan lagi 8x bimbingan online).
     */
    public function isSidangEligible(ThesisSubmission $submission): bool
    {
        return !empty($submission->logbook_file_path);
    }

    /**
     * Eligibility summary untuk ditampilkan ke UI.
     */
    public function getSummary(Mahasiswa $mahasiswa): array
    {
        $totalSks     = $this->getTotalSksLulus($mahasiswa);
        $isEligible   = $totalSks >= self::MIN_SKS;
        $submission   = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->first();

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
