<?php

namespace App\Domain\Thesis\Services;

use App\Models\Admin;
use App\Models\ThesisSidangRegistration;
use App\Models\ThesisSidangSchedule;
use App\Models\ThesisSidangFile;
use App\Models\ThesisSubmission;
use App\Domain\Thesis\Enums\ThesisStatus;

class ThesisSidangService
{
    public function __construct(
        private ThesisWorkflowService $workflow,
        private ThesisFileService $fileService
    ) {}

    /**
     * Initialize a draft sidang registration (idempotent).
     */
    public function initRegistration(ThesisSubmission $submission): ThesisSidangRegistration
    {
        $reg = ThesisSidangRegistration::firstOrCreate(
            ['thesis_submission_id' => $submission->id, 'status' => 'draft'],
        );

        // Sync status to submission
        if ($submission->status === ThesisStatus::ELIGIBLE_SIDANG) {
            $submission->update(['status' => ThesisStatus::SIDANG_REG_DRAFT]);
        }

        return $reg;
    }

    /**
     * Upload / replace a single file on the registration.
     */
    public function upsertFile(
        ThesisSidangRegistration $reg,
        string $type,
        \Illuminate\Http\UploadedFile $file,
        \App\Models\Mahasiswa $mahasiswa
    ): ThesisSidangFile {
        // Remove old file of same type
        $existing = $reg->files()->where('file_type', $type)->first();
        if ($existing) {
            $this->fileService->delete($existing->file_path);
            $existing->delete();
        }

        $path = $this->fileService->storeSidangFile($mahasiswa, $type, $file);

        return $reg->files()->create([
            'file_type'    => $type,
            'file_path'    => $path,
            'original_name'=> $file->getClientOriginalName(),
            'file_size'    => $file->getSize(),
        ]);
    }

    /**
     * Submit the registration for admin review.
     */
    public function submit(ThesisSidangRegistration $reg): void
    {
        if (! $reg->hasRequiredFiles()) {
            throw new \RuntimeException('File wajib belum lengkap. Harap upload semua dokumen yang diperlukan.');
        }

        $this->workflow->submitSidangRegistration($reg);
    }

    /**
     * Admin schedules the sidang.
     */
    public function schedule(ThesisSubmission $submission, array $data, Admin $admin): ThesisSidangSchedule
    {
        // Create schedule record
        $schedule = ThesisSidangSchedule::create([
            'thesis_submission_id'  => $submission->id,
            'sidang_registration_id'=> $submission->sidangRegistration?->id,
            'tanggal'               => $data['tanggal'],
            'waktu_mulai'           => $data['waktu_mulai'],
            'waktu_selesai'         => $data['waktu_selesai'] ?? null,
            'ruangan_id'            => $data['ruangan_id'] ?? null,
            'ruangan_manual'        => $data['ruangan_manual'] ?? null,
            'pembimbing_id'         => $data['pembimbing_id'],
            'penguji_1_id'          => $data['penguji_1_id'],
            'penguji_2_id'          => $data['penguji_2_id'] ?? null,
            'notes'                 => $data['notes'] ?? null,
            'created_by'            => $admin->id,
        ]);

        $this->workflow->scheduleSidang($submission);

        return $schedule;
    }
}
