<?php

namespace App\Domain\Thesis\Services;

use App\Domain\Thesis\Enums\ThesisStatus;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\ThesisSubmission;
use Illuminate\Validation\ValidationException;

/**
 * Manages all status transitions for ThesisSubmission.
 * Only this service should call $submission->update(['status' => ...]).
 */
class ThesisWorkflowService
{
    // ── Proposal ──────────────────────────────────────────────────────────

    public function submitProposal(ThesisSubmission $submission): void
    {
        // Create a supervisor confirmation step before sending to admin
        $this->assertCanTransition($submission->status, ThesisStatus::PROPOSAL_PENDING_SUPERVISOR);
        $submission->update([
            'status'       => ThesisStatus::PROPOSAL_PENDING_SUPERVISOR,
            'admin_note'   => null,
        ]);
    }

    /**
     * Called when the requested supervisor accepts the student's request.
     * Moves submission onward to admin review.
     */
    public function supervisorAcceptProposal(ThesisSubmission $submission, Dosen $dosen): void
    {
        // Only the requested supervisor may accept
        if ($submission->requested_supervisor_id !== $dosen->id) {
            throw ValidationException::withMessages(['supervisor' => 'Hanya dosen yang diminta dapat menerima permintaan ini.']);
        }

        $this->assertCanTransition($submission->status, ThesisStatus::PROPOSAL_SUBMITTED);
        $submission->update([
            'status' => ThesisStatus::PROPOSAL_SUBMITTED,
        ]);
    }

    /**
     * Called when the requested supervisor rejects the request.
     * Reverts submission back to draft so mahasiswa can pick another supervisor.
     */
    public function supervisorRejectProposal(ThesisSubmission $submission, Dosen $dosen, string $note = null): void
    {
        if ($submission->requested_supervisor_id !== $dosen->id) {
            throw ValidationException::withMessages(['supervisor' => 'Hanya dosen yang diminta dapat menolak permintaan ini.']);
        }

        $this->assertCanTransition($submission->status, ThesisStatus::PROPOSAL_DRAFT);
        $submission->update([
            'status'     => ThesisStatus::PROPOSAL_DRAFT,
            'admin_note' => $note,
        ]);
    }

    public function approveProposal(ThesisSubmission $submission, Admin $admin, string $note = null): void
    {
        $this->assertCanTransition($submission->status, ThesisStatus::PROPOSAL_APPROVED);
        $submission->update([
            'status'               => ThesisStatus::BIMBINGAN_ACTIVE,
            'approved_supervisor_id'=> $submission->requested_supervisor_id,
            'reviewed_by'          => $admin->id,
            'admin_note'           => $note,
        ]);
    }

    public function rejectProposal(ThesisSubmission $submission, Admin $admin, string $reason): void
    {
        $this->assertCanTransition($submission->status, ThesisStatus::PROPOSAL_REJECTED);
        $submission->update([
            'status'      => ThesisStatus::PROPOSAL_REJECTED,
            'reviewed_by' => $admin->id,
            'admin_note'  => $reason,
        ]);
    }

    // ── Bimbingan ─────────────────────────────────────────────────────────

    /**
     * Called after a new ThesisGuidance is approved — increments counter
     * and upgrades status to ELIGIBLE_SIDANG if threshold reached.
     */
    public function recalculateBimbingan(ThesisSubmission $submission): void
    {
        $approved = $submission->approvedGuidances()->count();

        $updates = ['total_bimbingan' => $approved];

        if (
            $approved >= ThesisEligibilityService::MIN_BIMBINGAN
            && $submission->status === ThesisStatus::BIMBINGAN_ACTIVE
        ) {
            $updates['status']                = ThesisStatus::ELIGIBLE_SIDANG;
            $updates['eligible_for_sidang_at'] = now();
        }

        $submission->update($updates);
    }

    // ── Sidang Registration ───────────────────────────────────────────────

    public function submitSidangRegistration(\App\Models\ThesisSidangRegistration $reg): void
    {
        $reg->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);
        $reg->submission->update(['status' => ThesisStatus::SIDANG_REG_SUBMITTED]);
    }

    public function verifySidangRegistration(
        \App\Models\ThesisSidangRegistration $reg,
        Admin $admin,
        string $note = null
    ): void {
        $reg->update([
            'status'      => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'admin_note'  => $note,
        ]);
        // Admin will then schedule → changes to SIDANG_SCHEDULED
    }

    public function rejectSidangRegistration(
        \App\Models\ThesisSidangRegistration $reg,
        Admin $admin,
        string $reason
    ): void {
        $reg->update([
            'status'      => 'rejected',
            'verified_by' => $admin->id,
            'rejected_at' => now(),
            'admin_note'  => $reason,
        ]);
        $reg->submission->update([
            'status' => ThesisStatus::SIDANG_REG_REJECTED,
        ]);
    }

    public function scheduleSidang(ThesisSubmission $submission): void
    {
        $submission->update(['status' => ThesisStatus::SIDANG_SCHEDULED]);
    }

    public function completeSidang(ThesisSubmission $submission): void
    {
        $this->assertCanTransition($submission->status, ThesisStatus::SIDANG_COMPLETED);
        $submission->update(['status' => ThesisStatus::REVISION_PENDING]);
    }

    // ── Revision ──────────────────────────────────────────────────────────

    public function uploadRevision(ThesisSubmission $submission): void
    {
        $submission->update(['status' => ThesisStatus::REVISION_UPLOADED]);
    }

    public function approveRevision(ThesisSubmission $submission, \App\Models\Dosen $dosen): void
    {
        $submission->update([
            'status'               => ThesisStatus::THESIS_COMPLETED,
            'revision_approved_at' => now(),
        ]);

        // Update the latest revision record
        $submission->latestRevision?->update([
            'approved_by_dosen_id' => $dosen->id,
            'approved_at'          => now(),
        ]);
    }

    // ── Guard ─────────────────────────────────────────────────────────────

    private function assertCanTransition(ThesisStatus $from, ThesisStatus $to): void
    {
        if (! $from->canTransitionTo($to)) {
            throw ValidationException::withMessages([
                'status' => "Tidak dapat berpindah dari status [{$from->label()}] ke [{$to->label()}].",
            ]);
        }
    }
}
