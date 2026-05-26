<?php

namespace App\Domain\Wisuda\Services;

use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\WisudaBatch;
use App\Models\WisudaDocument;
use App\Models\WisudaRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Core wisuda workflow service.
 */
class WisudaService
{
    public function __construct(
        private WisudaFileService $fileService,
        private WisudaNotificationService $notificationService,
    ) {}

    // ── Registration ─────────────────────────────────────────────────────

    /**
     * Create a new wisuda registration.
     */
    public function createRegistration(
        Mahasiswa $mahasiswa,
        int $skripsiSubmissionId,
        string $noHp,
        string $emailAktif
    ): WisudaRegistration {
        return WisudaRegistration::create([
            'mahasiswa_id'          => $mahasiswa->id,
            'skripsi_submission_id' => $skripsiSubmissionId,
            'no_hp'                 => $noHp,
            'email_aktif'           => $emailAktif,
            'status'                => WisudaRegistrationStatus::PENDING,
            'submitted_at'          => now(),
        ]);
    }

    /**
     * Upload or replace a document on a registration.
     */
    public function upsertDocument(
        WisudaRegistration $reg,
        string $type,
        UploadedFile $file,
        Mahasiswa $mahasiswa
    ): WisudaDocument {
        $existing = $reg->documents()->where('file_type', $type)->first();
        if ($existing) {
            $this->fileService->delete($existing->file_path);
            $existing->delete();
        }

        $path = $this->fileService->storeDocument($mahasiswa, $type, $file);

        return $reg->documents()->create([
            'file_type'     => $type,
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size'     => $file->getSize(),
        ]);
    }

    // ── Admin Actions ────────────────────────────────────────────────────

    /**
     * Approve a registration.
     */
    public function approve(WisudaRegistration $reg, User $admin): void
    {
        $reg->update([
            'status'      => WisudaRegistrationStatus::APPROVED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Reject a registration.
     */
    public function reject(WisudaRegistration $reg, User $admin, string $reason): void
    {
        $reg->update([
            'status'         => WisudaRegistrationStatus::REJECTED,
            'rejection_note' => $reason,
            'reviewed_by'    => $admin->id,
            'reviewed_at'    => now(),
        ]);
    }

    // ── Batch Management ─────────────────────────────────────────────────

    /**
     * Create a new wisuda batch.
     */
    public function createBatch(array $data, User $admin): WisudaBatch
    {
        return WisudaBatch::create([
            'nama_batch'  => $data['nama_batch'],
            'tanggal'     => $data['tanggal'],
            'waktu_mulai' => $data['waktu_mulai'],
            'lokasi'      => $data['lokasi'],
            'catatan'     => $data['catatan'] ?? null,
            'created_by'  => $admin->id,
        ]);
    }

    /**
     * Assign approved registrations to a batch → set status scheduled → notify.
     */
    public function assignToBatch(WisudaBatch $batch, array $registrationIds): void
    {
        DB::transaction(function () use ($batch, $registrationIds) {
            WisudaRegistration::whereIn('id', $registrationIds)
                ->where('status', WisudaRegistrationStatus::APPROVED)
                ->whereNull('wisuda_batch_id')
                ->update([
                    'wisuda_batch_id' => $batch->id,
                    'status'          => WisudaRegistrationStatus::SCHEDULED,
                ]);
        });

        $this->notificationService->notifyScheduled($batch, $registrationIds);
    }

    /**
     * Update batch details → re-notify if mahasiswa are already scheduled.
     */
    public function updateBatch(WisudaBatch $batch, array $data): void
    {
        $batch->update([
            'tanggal'     => $data['tanggal'] ?? $batch->tanggal,
            'waktu_mulai' => $data['waktu_mulai'] ?? $batch->waktu_mulai,
            'lokasi'      => $data['lokasi'] ?? $batch->lokasi,
            'catatan'     => $data['catatan'] ?? $batch->catatan,
        ]);

        // Re-notify all scheduled mahasiswa in this batch
        $this->notificationService->notifyBatchUpdated($batch->fresh());
    }
}
