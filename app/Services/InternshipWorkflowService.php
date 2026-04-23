<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\InternshipRevision;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Orchestrates all state transitions for the internship workflow.
 * Single responsibility: validate & execute status changes + side effects.
 */
class InternshipWorkflowService
{
    public function __construct(
        private InternshipLetterService $letterService,
        private InternshipKrsService $krsService,
    ) {}

    // ─────────────────────────────────────────────────────────────
    //  Mahasiswa actions
    // ─────────────────────────────────────────────────────────────

    /**
     * Create a new internship draft.
     */
    public function createDraft(int $mahasiswaId, int $semesterId, array $data): Internship
    {
        return Internship::create(array_merge($data, [
            'mahasiswa_id' => $mahasiswaId,
            'semester_id'  => $semesterId,
            'status'       => Internship::STATUS_DRAFT,
        ]));
    }

    /**
     * Mahasiswa submits the draft → moves to SUBMITTED then immediately to WAITING_REQUEST_LETTER.
     */
    public function submit(Internship $internship): void
    {
        $internship->transitionTo(Internship::STATUS_SUBMITTED);
        // Auto-advance: mahasiswa must now create the request letter
        $internship->transitionTo(Internship::STATUS_WAITING_REQUEST_LETTER);
    }

    /**
     * Generate the "Surat Permohonan Magang" from template + auto-fill.
     * Returns the path to the generated document.
     */
    public function generateRequestLetter(Internship $internship): string
    {
        if ($internship->status !== Internship::STATUS_WAITING_REQUEST_LETTER
            && $internship->status !== Internship::STATUS_REJECTED) {
            throw new \LogicException('Surat permohonan hanya bisa digenerate pada status yang sesuai.');
        }

        $path = $this->letterService->generateRequestLetter($internship);

        $internship->update(['request_letter_generated_path' => $path]);

        return $path;
    }

    /**
     * Mahasiswa uploads the signed request letter → advance to REQUEST_LETTER_UPLOADED.
     */
    public function uploadSignedRequestLetter(Internship $internship, $file): void
    {
        $targetFolder = 'internship/signed/' . $internship->mahasiswa->storage_folder;
        $fileName = 'internship_request_signed_' . $internship->id . '_' . time()
                    . '.' . $file->getClientOriginalExtension();
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $fileName);
        $path = $file->storeAs($targetFolder, $fileName, $resolvedDisk);

        $internship->update([
            'request_letter_signed_path' => $path,
        ]);

        if ($internship->status === Internship::STATUS_WAITING_REQUEST_LETTER) {
            $internship->transitionTo(Internship::STATUS_REQUEST_LETTER_UPLOADED);
        }
    }

    /**
     * Mahasiswa submits for review → advance to UNDER_REVIEW.
     */
    public function submitForReview(Internship $internship, ?string $note = null): void
    {
        $internship->transitionTo(Internship::STATUS_UNDER_REVIEW);

        // Track revision if this is a resubmission
        if ($internship->revision_no > 0) {
            InternshipRevision::create([
                'internship_id'             => $internship->id,
                'revision_no'               => $internship->revision_no,
                'request_letter_signed_path' => $internship->request_letter_signed_path,
                'note_from_mahasiswa'        => $note,
            ]);
        }
    }

    /**
     * Update internship data (only editable statuses: draft / rejected).
     */
    public function updateData(Internship $internship, array $data): void
    {
        if (!$internship->isEditable()) {
            throw new \LogicException('Data magang hanya bisa diubah saat draft atau setelah ditolak.');
        }
        $internship->update($data);
    }

    // ─────────────────────────────────────────────────────────────
    //  Admin / Akademik actions
    // ─────────────────────────────────────────────────────────────

    /**
     * Approve the internship request.
     */
    public function approve(Internship $internship, int $userId, ?string $adminNote = null): void
    {
        $internship->transitionTo(Internship::STATUS_APPROVED);
        $internship->update([
            'approved_by' => $userId,
            'approved_at' => now(),
            'admin_note'  => $adminNote,
            'rejected_reason' => null,
        ]);
    }

    /**
     * Reject the internship request. Mahasiswa can revise and resubmit.
     */
    public function reject(Internship $internship, int $userId, string $reason): void
    {
        $internship->transitionTo(Internship::STATUS_REJECTED);
        $internship->update([
            'rejected_reason' => $reason,
            'rejected_at'     => now(),
            'revision_no'     => $internship->revision_no + 1,
        ]);

        InternshipRevision::create([
            'internship_id'  => $internship->id,
            'revision_no'    => $internship->revision_no,
            'note_from_admin' => $reason,
        ]);
    }

    /**
     * Assign a supervisor (dosen pembimbing) → advance to SUPERVISOR_ASSIGNED.
     */
    public function assignSupervisor(Internship $internship, int $dosenId): void
    {
        $internship->transitionTo(Internship::STATUS_SUPERVISOR_ASSIGNED);
        $internship->update([
            'supervisor_dosen_id'  => $dosenId,
            'supervisor_assigned_at' => now(),
        ]);
    }

    /**
     * Mahasiswa uploads the acceptance letter from the company (instansi).
     * Only saves the file — does NOT advance status.
     * Admin must confirm separately via receiveAcceptanceLetter().
     */
    public function saveMahasiswaAcceptanceLetter(Internship $internship, \Illuminate\Http\UploadedFile $file): void
    {
        $targetFolder = 'internship/acceptance/' . $internship->mahasiswa->storage_folder;
        $ext  = $file->getClientOriginalExtension();
        $name = 'acceptance_' . $internship->id . '_' . time() . '.' . $ext;
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $name);
        $path = $file->storeAs($targetFolder, $name, $resolvedDisk);
        $internship->update(['acceptance_letter_path' => $path]);
    }

    /**
     * Admin confirms that acceptance letter has been received/reviewed.
     * Status advances to ACCEPTANCE_LETTER_READY so the scheduler can start magang on periode_mulai.
     */
    public function receiveAcceptanceLetter(Internship $internship, ?\Illuminate\Http\UploadedFile $file = null): void
    {
        if (!$internship->acceptance_letter_path && !$file) {
            throw new \LogicException('Mahasiswa belum mengunggah surat penerimaan. Tunggu mahasiswa mengupload terlebih dahulu.');
        }

        if ($file) {
            $targetFolder = 'internship/acceptance/' . $internship->mahasiswa->storage_folder;
            $ext  = $file->getClientOriginalExtension();
            $name = 'acceptance_' . $internship->id . '_' . time() . '.' . $ext;
            $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $name);
            $path = $file->storeAs($targetFolder, $name, $resolvedDisk);
            $internship->update(['acceptance_letter_path' => $path]);
        }

        $internship->transitionTo(Internship::STATUS_ACCEPTANCE_LETTER_READY);
    }

    /**
     * Generate official PDF (Surat Permohonan Resmi) by admin, with nomor surat.
     * Does NOT change status — admin still needs to upload signed version + send.
     */
    public function generateOfficialPdf(Internship $internship, string $nomorSurat): string
    {
        if (!in_array($internship->status, [Internship::STATUS_APPROVED, Internship::STATUS_SENT_TO_STUDENT])) {
            throw new \LogicException('PDF resmi hanya bisa digenerate setelah status Disetujui.');
        }

        $internship->update(['nomor_surat' => $nomorSurat]);

        $path = $this->letterService->generateOfficialPdf($internship);
        $internship->update(['admin_final_pdf_path' => $path]);

        return $path;
    }

    /**
     * Admin uploads signed/stamped PDF (admin_signed_pdf_path).
     * Does NOT change status.
     */
    public function uploadAdminSignedPdf(Internship $internship, $file): void
    {
        $targetFolder = 'internship/admin_signed/' . $internship->mahasiswa->storage_folder;
        $ext      = $file->getClientOriginalExtension();
        $fileName = 'official_signed_' . $internship->id . '_' . time() . '.' . $ext;
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $fileName);
        $path     = $file->storeAs($targetFolder, $fileName, $resolvedDisk);

        $internship->update(['admin_signed_pdf_path' => $path]);
    }

    /**
     * Admin sends the official letter to student → advance to SENT_TO_STUDENT.
     */
    public function sendToStudent(Internship $internship, int $adminUserId): void
    {
        if (!$internship->admin_signed_pdf_path && !$internship->admin_final_pdf_path) {
            throw new \LogicException('Generate dan upload PDF resmi terlebih dahulu sebelum mengirim ke mahasiswa.');
        }

        $internship->transitionTo(Internship::STATUS_SENT_TO_STUDENT);
        $internship->update([
            'sent_to_student_at' => now(),
            'sent_by'            => $adminUserId,
        ]);
    }

    /**
     * Update internship period dates with audit trail.
     * Can be called when status is APPROVED or beyond (except CLOSED/GRADED).
     */
    public function updatePeriodDates(
        Internship $internship,
        string $periodeMulai,
        string $periodeSelesai,
        int $changedByUserId,
        string $reason
    ): void {
        $allowed = [
            Internship::STATUS_APPROVED,
            Internship::STATUS_SENT_TO_STUDENT,
            Internship::STATUS_SUPERVISOR_ASSIGNED,
            Internship::STATUS_ACCEPTANCE_LETTER_READY,
            Internship::STATUS_ONGOING,
            Internship::STATUS_COMPLETED,
        ];

        if (!in_array($internship->status, $allowed)) {
            throw new \LogicException('Tanggal magang hanya bisa diubah setelah status Disetujui sampai Selesai.');
        }

        $internship->update([
            'periode_mulai'     => $periodeMulai,
            'periode_selesai'   => $periodeSelesai,
            'date_changed_by'   => $changedByUserId,
            'date_changed_at'   => now(),
            'date_change_reason' => $reason,
        ]);

        // Auto-sync status based on new dates
        $this->syncStatusByDates($internship);
    }

    /**
     * Auto-transition status ONGOING/COMPLETED based on current dates.
     * Called by scheduler and after date updates.
     */
    public function syncStatusByDates(Internship $internship): void
    {
        $today = now()->startOfDay();

        // ACCEPTANCE_LETTER_READY → ONGOING when start date arrives
        if (
            $internship->status === Internship::STATUS_ACCEPTANCE_LETTER_READY
            && $internship->periode_mulai
            && $internship->periode_mulai->lte($today)
        ) {
            try {
                $this->startInternship($internship);
            } catch (\Throwable $e) {
                Log::warning("syncStatusByDates: could not auto-start internship #{$internship->id}: " . $e->getMessage());
            }
            return;
        }

        // ONGOING → COMPLETED when end date has passed
        if (
            $internship->status === Internship::STATUS_ONGOING
            && $internship->periode_selesai
            && $internship->periode_selesai->lt($today)
        ) {
            try {
                $this->markCompleted($internship);
            } catch (\Throwable $e) {
                Log::warning("syncStatusByDates: could not auto-complete internship #{$internship->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Start internship → ONGOING. Also inject KRS conversion courses.
     */
    public function startInternship(Internship $internship): void
    {
        $internship->transitionTo(Internship::STATUS_ONGOING);

        // Auto-inject KRS konversi magang
        try {
            $this->krsService->injectConversionCourses($internship);
        } catch (\Throwable $e) {
            Log::warning("InternshipWorkflow: gagal inject KRS konversi untuk internship #{$internship->id}: " . $e->getMessage());
        }
    }

    /**
     * Mark internship as completed (magang selesai).
     */
    public function markCompleted(Internship $internship): void
    {
        $internship->transitionTo(Internship::STATUS_COMPLETED);
    }

    /**
     * Mark as graded (after akademik inputs nilai konversi).
     */
    public function markGraded(Internship $internship): void
    {
        $internship->transitionTo(Internship::STATUS_GRADED);
    }

    /**
     * Close the internship.
     */
    public function close(Internship $internship): void
    {
        $internship->transitionTo(Internship::STATUS_CLOSED);
    }

    /**
     * Delete an internship (only if draft/rejected).
     */
    public function delete(Internship $internship): void
    {
        if (!in_array($internship->status, [Internship::STATUS_DRAFT, Internship::STATUS_REJECTED])) {
            throw new \LogicException('Hanya pengajuan draft atau ditolak yang bisa dihapus.');
        }

        // Delete files
        foreach (['request_letter_generated_path', 'request_letter_signed_path', 'acceptance_letter_path', 'dokumen_pendukung_path'] as $field) {
            if ($internship->$field) {
                Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($internship->$field))->delete($internship->$field);
            }
        }

        $internship->revisions()->delete();
        $internship->courseMappings()->delete();
        $internship->logbooks()->delete();
        $internship->delete();
    }
}
