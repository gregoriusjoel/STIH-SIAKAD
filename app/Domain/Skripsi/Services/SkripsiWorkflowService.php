<?php

namespace App\Domain\Skripsi\Services;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\SkripsiSubmission;
use Illuminate\Validation\ValidationException;

/**
 * Manages all status transitions for SkripsiSubmission.
 * Only this service should call $submission->update(['status' => ...]).
 */
class SkripsiWorkflowService
{
    // ── Proposal ──────────────────────────────────────────────────────────

    public function submitProposal(SkripsiSubmission $submission): void
    {
        $this->assertCanTransition($submission->status, SkripsiStatus::PROPOSAL_PENDING_SUPERVISOR);
        $submission->update([
            'status'       => SkripsiStatus::PROPOSAL_PENDING_SUPERVISOR,
            'admin_note'   => null,
        ]);
    }

    public function supervisorAcceptProposal(SkripsiSubmission $submission, Dosen $dosen): void
    {
        if ($submission->requested_supervisor_id !== $dosen->id) {
            throw ValidationException::withMessages(['supervisor' => 'Hanya dosen yang diminta dapat menerima permintaan ini.']);
        }

        $this->assertCanTransition($submission->status, SkripsiStatus::PROPOSAL_SUBMITTED);
        $submission->update([
            'status' => SkripsiStatus::PROPOSAL_SUBMITTED,
        ]);
    }

    public function supervisorRejectProposal(SkripsiSubmission $submission, Dosen $dosen, string $note = null): void
    {
        if ($submission->requested_supervisor_id !== $dosen->id) {
            throw ValidationException::withMessages(['supervisor' => 'Hanya dosen yang diminta dapat menolak permintaan ini.']);
        }

        $this->assertCanTransition($submission->status, SkripsiStatus::PROPOSAL_DRAFT);
        $submission->update([
            'status'     => SkripsiStatus::PROPOSAL_DRAFT,
            'admin_note' => $note,
        ]);
    }

    public function approveProposal(SkripsiSubmission $submission, Admin $admin, string $note = null): void
    {
        $this->assertCanTransition($submission->status, SkripsiStatus::PROPOSAL_APPROVED);
        $submission->update([
            'status'               => SkripsiStatus::BIMBINGAN_ACTIVE,
            'approved_supervisor_id'=> $submission->requested_supervisor_id,
            'reviewed_by'          => $admin->id,
            'admin_note'           => $note,
        ]);
    }

    public function rejectProposal(SkripsiSubmission $submission, Admin $admin, string $reason): void
    {
        $this->assertCanTransition($submission->status, SkripsiStatus::PROPOSAL_REJECTED);
        $submission->update([
            'status'      => SkripsiStatus::PROPOSAL_REJECTED,
            'reviewed_by' => $admin->id,
            'admin_note'  => $reason,
        ]);
    }

    // ── Bimbingan ─────────────────────────────────────────────────────────

    public function recalculateBimbingan(SkripsiSubmission $submission): void
    {
        $approved = $submission->approvedGuidances()->count();

        $updates = ['total_bimbingan' => $approved];

        if (
            $approved >= SkripsiEligibilityService::MIN_BIMBINGAN
            && $submission->status === SkripsiStatus::BIMBINGAN_ACTIVE
        ) {
            $updates['status']                = SkripsiStatus::ELIGIBLE_SIDANG;
            $updates['eligible_for_sidang_at'] = now();
        }

        $submission->update($updates);
    }

    // ── Logbook Upload ────────────────────────────────────────────────────

    public function uploadLogbook(SkripsiSubmission $submission): void
    {
        if ($submission->status === SkripsiStatus::BIMBINGAN_ACTIVE) {
            $submission->update([
                'status'                => SkripsiStatus::ELIGIBLE_SIDANG,
                'eligible_for_sidang_at'=> now(),
            ]);
        }
    }

    // ── Sidang Registration ───────────────────────────────────────────────

    public function submitSidangRegistration(\App\Models\SkripsiSidangRegistration $reg): void
    {
        $reg->update([
            'status'       => 'submitted',
            'submitted_at' => now(),
        ]);
        $reg->submission->update(['status' => SkripsiStatus::SIDANG_REG_SUBMITTED]);
    }

    public function verifySidangRegistration(
        \App\Models\SkripsiSidangRegistration $reg,
        Admin $admin,
        string $note = null
    ): void {
        $reg->update([
            'status'      => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'admin_note'  => $note,
        ]);
    }

    public function rejectSidangRegistration(
        \App\Models\SkripsiSidangRegistration $reg,
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
            'status' => SkripsiStatus::SIDANG_REG_REJECTED,
        ]);
    }

    public function scheduleSidang(SkripsiSubmission $submission): void
    {
        $submission->update(['status' => SkripsiStatus::SIDANG_SCHEDULED]);
    }

    public function completeSidang(SkripsiSubmission $submission): void
    {
        $this->assertCanTransition($submission->status, SkripsiStatus::SIDANG_COMPLETED);
        $submission->update(['status' => SkripsiStatus::REVISION_PENDING]);
    }

    // ── Revision ──────────────────────────────────────────────────────────

    public function uploadRevision(SkripsiSubmission $submission): void
    {
        $submission->update(['status' => SkripsiStatus::REVISION_UPLOADED]);
    }

    public function approveRevision(SkripsiSubmission $submission, \App\Models\Dosen $dosen): void
    {
        $submission->update([
            'status'               => SkripsiStatus::SKRIPSI_COMPLETED,
            'revision_approved_at' => now(),
        ]);

        $submission->latestRevision?->update([
            'approved_by_dosen_id' => $dosen->id,
            'approved_at'          => now(),
        ]);
    }

    // ── Guard ─────────────────────────────────────────────────────────────

    private function assertCanTransition(SkripsiStatus $from, SkripsiStatus $to): void
    {
        if (! $from->canTransitionTo($to)) {
            throw ValidationException::withMessages([
                'status' => "Tidak dapat berpindah dari status [{$from->label()}] ke [{$to->label()}].",
            ]);
        }
    }
}
