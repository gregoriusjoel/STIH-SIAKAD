<?php

namespace App\Domain\Skripsi\Services;

use App\Domain\Skripsi\Enums\GuidanceStatus;
use App\Models\Dosen;
use App\Models\SkripsiGuidance;
use App\Models\SkripsiSubmission;

class SkripsiGuidanceService
{
    public function __construct(
        private SkripsiWorkflowService $workflow
    ) {}

    public function create(SkripsiSubmission $submission, array $data): SkripsiGuidance
    {
        $guidance = $submission->guidances()->create([
            'dosen_id'          => $submission->approved_supervisor_id,
            'tanggal_bimbingan' => $data['tanggal_bimbingan'],
            'catatan'           => $data['catatan'],
            'file_path'         => $data['file_path'] ?? null,
            'status'            => GuidanceStatus::PENDING,
        ]);

        return $guidance;
    }

    public function approve(SkripsiGuidance $guidance, Dosen $dosen, string $note = null): void
    {
        $guidance->update([
            'status'       => GuidanceStatus::APPROVED,
            'catatan_dosen'=> $note,
            'reviewed_at'  => now(),
        ]);

        $this->workflow->recalculateBimbingan($guidance->submission);
    }

    public function reject(SkripsiGuidance $guidance, Dosen $dosen, string $note): void
    {
        $guidance->update([
            'status'       => GuidanceStatus::REJECTED,
            'catatan_dosen'=> $note,
            'reviewed_at'  => now(),
        ]);
    }
}
