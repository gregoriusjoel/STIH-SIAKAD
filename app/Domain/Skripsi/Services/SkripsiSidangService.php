<?php

namespace App\Domain\Skripsi\Services;

use App\Models\Admin;
use App\Models\SkripsiSidangRegistration;
use App\Models\SkripsiSidangSchedule;
use App\Models\SkripsiSidangFile;
use App\Models\SkripsiSubmission;
use App\Domain\Skripsi\Enums\SkripsiStatus;

class SkripsiSidangService
{
    public function __construct(
        private SkripsiWorkflowService $workflow,
        private SkripsiFileService $fileService
    ) {}

    public function initRegistration(SkripsiSubmission $submission): SkripsiSidangRegistration
    {
        $reg = SkripsiSidangRegistration::firstOrCreate(
            ['skripsi_submission_id' => $submission->id, 'status' => 'draft'],
        );

        if ($submission->status === SkripsiStatus::ELIGIBLE_SIDANG) {
            $submission->update(['status' => SkripsiStatus::SIDANG_REG_DRAFT]);
        }

        return $reg;
    }

    public function upsertFile(
        SkripsiSidangRegistration $reg,
        string $type,
        \Illuminate\Http\UploadedFile $file,
        \App\Models\Mahasiswa $mahasiswa
    ): SkripsiSidangFile {
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

    public function submit(SkripsiSidangRegistration $reg): void
    {
        if (! $reg->hasRequiredFiles()) {
            throw new \RuntimeException('File wajib belum lengkap. Harap upload semua dokumen yang diperlukan.');
        }

        $this->workflow->submitSidangRegistration($reg);
    }

    public function schedule(SkripsiSubmission $submission, array $data, Admin $admin): SkripsiSidangSchedule
    {
        $schedule = SkripsiSidangSchedule::create([
            'skripsi_submission_id'  => $submission->id,
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
