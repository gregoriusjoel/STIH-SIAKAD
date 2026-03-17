<?php

namespace App\Domain\Thesis\Services;

use App\Domain\Thesis\Enums\GuidanceStatus;
use App\Models\Dosen;
use App\Models\ThesisGuidance;
use App\Models\ThesisSubmission;

class ThesisGuidanceService
{
    public function __construct(
        private ThesisWorkflowService $workflow
    ) {}

    public function create(ThesisSubmission $submission, array $data): ThesisGuidance
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

    public function approve(ThesisGuidance $guidance, Dosen $dosen, string $note = null): void
    {
        $guidance->update([
            'status'       => GuidanceStatus::APPROVED,
            'catatan_dosen'=> $note,
            'reviewed_at'  => now(),
        ]);

        // Recalculate counter & potentially upgrade status
        $this->workflow->recalculateBimbingan($guidance->submission);
    }

    public function reject(ThesisGuidance $guidance, Dosen $dosen, string $note): void
    {
        $guidance->update([
            'status'       => GuidanceStatus::REJECTED,
            'catatan_dosen'=> $note,
            'reviewed_at'  => now(),
        ]);
    }
}
