<?php

namespace App\Services;

use App\Models\Prestasi;
use App\Models\PrestasiDokumen;
use App\Models\PrestasiLog;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Orchestrates all state transitions and business logic for the Prestasi workflow.
 */
class PrestasiService
{
    // ─────────────────────────────────────────────────────────────
    //  Create / Update
    // ─────────────────────────────────────────────────────────────

    /**
     * Create a new prestasi draft.
     */
    public function createDraft(string $pengajuType, int $pengajuId, array $data): Prestasi
    {
        // Generate hash for duplicate detection
        $hash = Prestasi::generateHash(
            $data['nama_kegiatan'],
            $data['penyelenggara'],
            $data['tanggal_mulai'],
            $pengajuType,
            $pengajuId
        );

        // Check for duplicate
        $existing = Prestasi::where('hash_kegiatan', $hash)
            ->whereNotIn('status', [Prestasi::STATUS_DITOLAK])
            ->first();

        if ($existing) {
            throw new \LogicException('Kegiatan ini sudah pernah diajukan sebelumnya.');
        }

        $prestasi = Prestasi::create(array_merge($data, [
            'pengaju_type'  => $pengajuType,
            'pengaju_id'    => $pengajuId,
            'status'        => Prestasi::STATUS_DRAFT,
            'hash_kegiatan' => $hash,
        ]));

        $this->logAction($prestasi, 'created', null, Prestasi::STATUS_DRAFT);

        return $prestasi;
    }

    /**
     * Update prestasi data (only editable statuses: draft / ditolak).
     */
    public function updateData(Prestasi $prestasi, array $data): void
    {
        if (!$prestasi->isEditable()) {
            throw new \LogicException('Data hanya bisa diubah saat draft atau setelah ditolak.');
        }

        // Regenerate hash if key fields changed
        if (isset($data['nama_kegiatan']) || isset($data['penyelenggara']) || isset($data['tanggal_mulai'])) {
            $data['hash_kegiatan'] = Prestasi::generateHash(
                $data['nama_kegiatan'] ?? $prestasi->nama_kegiatan,
                $data['penyelenggara'] ?? $prestasi->penyelenggara,
                $data['tanggal_mulai'] ?? $prestasi->tanggal_mulai->format('Y-m-d'),
                $prestasi->pengaju_type,
                $prestasi->pengaju_id
            );
        }

        $prestasi->update($data);
        $this->logAction($prestasi, 'data_updated');
    }

    // ─────────────────────────────────────────────────────────────
    //  State transitions
    // ─────────────────────────────────────────────────────────────

    /**
     * Submit prestasi to admin.
     */
    public function submit(Prestasi $prestasi): void
    {
        $oldStatus = $prestasi->status;
        $prestasi->transitionTo(Prestasi::STATUS_DIAJUKAN);
        $this->logAction($prestasi, 'submitted', $oldStatus, Prestasi::STATUS_DIAJUKAN);
    }

    /**
     * Admin starts processing.
     */
    public function startProcessing(Prestasi $prestasi): void
    {
        $oldStatus = $prestasi->status;
        $prestasi->transitionTo(Prestasi::STATUS_DIPROSES_ADMIN);
        $this->logAction($prestasi, 'status_change', $oldStatus, Prestasi::STATUS_DIPROSES_ADMIN);
    }

    /**
     * Admin approves the prestasi.
     */
    public function approve(Prestasi $prestasi, int $userId, ?string $adminNote = null): void
    {
        // Allow transition from diajukan → diproses_admin first if needed
        if ($prestasi->status === Prestasi::STATUS_DIAJUKAN) {
            $prestasi->transitionTo(Prestasi::STATUS_DIPROSES_ADMIN);
        }

        $oldStatus = $prestasi->status;

        // For pelaporan without surat, go directly to selesai
        // For pengajuan, stay at diproses_admin until surat is generated
        $prestasi->update([
            'approved_by' => $userId,
            'approved_at' => now(),
            'admin_note'  => $adminNote,
            'rejected_reason' => null,
        ]);

        $this->logAction($prestasi, 'approved', $oldStatus, $prestasi->status, [
            'admin_note' => $adminNote,
        ]);
    }

    /**
     * Admin rejects the prestasi.
     */
    public function reject(Prestasi $prestasi, int $userId, string $reason): void
    {
        $oldStatus = $prestasi->status;
        $prestasi->transitionTo(Prestasi::STATUS_DITOLAK);

        $prestasi->update([
            'rejected_reason' => $reason,
            'rejected_at'     => now(),
        ]);

        $this->logAction($prestasi, 'rejected', $oldStatus, Prestasi::STATUS_DITOLAK, [
            'reason' => $reason,
        ]);
    }

    /**
     * Mark prestasi as surat diterbitkan.
     */
    public function markSuratDiterbitkan(Prestasi $prestasi): void
    {
        if ($prestasi->status !== Prestasi::STATUS_DIPROSES_ADMIN) {
            return; // Already advanced or in wrong state
        }

        $oldStatus = $prestasi->status;
        $prestasi->transitionTo(Prestasi::STATUS_SURAT_DITERBITKAN);
        $this->logAction($prestasi, 'status_change', $oldStatus, Prestasi::STATUS_SURAT_DITERBITKAN);
    }

    /**
     * Mark prestasi as selesai.
     */
    public function markSelesai(Prestasi $prestasi): void
    {
        $oldStatus = $prestasi->status;
        $prestasi->transitionTo(Prestasi::STATUS_SELESAI);
        $this->logAction($prestasi, 'status_change', $oldStatus, Prestasi::STATUS_SELESAI);
    }

    // ─────────────────────────────────────────────────────────────
    //  Document management
    // ─────────────────────────────────────────────────────────────

    /**
     * Upload a document for a prestasi.
     */
    public function uploadDokumen(Prestasi $prestasi, UploadedFile $file, string $jenis, int $userId): PrestasiDokumen
    {
        $folder = $prestasi->storage_folder . '/' . $jenis;
        $fileName = $jenis . '_' . $prestasi->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($folder . '/' . $fileName);
        $path = $file->storeAs($folder, $fileName, $resolvedDisk);

        $dokumen = PrestasiDokumen::create([
            'prestasi_id'  => $prestasi->id,
            'jenis'        => $jenis,
            'file_path'    => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'    => $file->getMimeType(),
            'size'         => $file->getSize(),
            'uploaded_by'  => $userId,
        ]);

        $this->logAction($prestasi, 'dokumen_uploaded', null, null, [
            'jenis' => $jenis,
            'file'  => $file->getClientOriginalName(),
        ]);

        return $dokumen;
    }

    /**
     * Delete a prestasi document.
     */
    public function deleteDokumen(PrestasiDokumen $dokumen): void
    {
        $disk = \App\Helpers\FileHelper::resolveDiskForPath($dokumen->file_path);
        if (Storage::disk($disk)->exists($dokumen->file_path)) {
            Storage::disk($disk)->delete($dokumen->file_path);
        }
        $dokumen->delete();
    }

    // ─────────────────────────────────────────────────────────────
    //  Admin note
    // ─────────────────────────────────────────────────────────────

    /**
     * Add admin note.
     */
    public function addNote(Prestasi $prestasi, string $note, int $userId): void
    {
        $prestasi->update(['admin_note' => $note]);
        $this->logAction($prestasi, 'note_added', null, null, ['note' => $note]);
    }

    /**
     * Verify sertifikat (for pelaporan type).
     */
    public function verifySertifikat(Prestasi $prestasi, int $userId): void
    {
        $this->logAction($prestasi, 'sertifikat_verified', null, null, [
            'verified_by' => $userId,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  Delete
    // ─────────────────────────────────────────────────────────────

    /**
     * Delete a prestasi (only if draft/ditolak).
     */
    public function delete(Prestasi $prestasi): void
    {
        if (!in_array($prestasi->status, [Prestasi::STATUS_DRAFT, Prestasi::STATUS_DITOLAK])) {
            throw new \LogicException('Hanya pengajuan draft atau ditolak yang bisa dihapus.');
        }

        // Delete all associated files
        foreach ($prestasi->dokumens as $dok) {
            $this->deleteDokumen($dok);
        }

        foreach ($prestasi->surats as $surat) {
            if ($surat->file_path) {
                $disk = \App\Helpers\FileHelper::resolveDiskForPath($surat->file_path);
                if (Storage::disk($disk)->exists($surat->file_path)) {
                    Storage::disk($disk)->delete($surat->file_path);
                }
            }
        }

        $prestasi->logs()->delete();
        $prestasi->surats()->delete();
        $prestasi->dokumens()->delete();
        $prestasi->delete();
    }

    // ─────────────────────────────────────────────────────────────
    //  Logging
    // ─────────────────────────────────────────────────────────────

    public function logAction(
        Prestasi $prestasi,
        string $action,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        ?array $metadata = null
    ): void {
        PrestasiLog::create([
            'prestasi_id' => $prestasi->id,
            'action'      => $action,
            'from_status' => $fromStatus,
            'to_status'   => $toStatus,
            'user_id'     => auth()->id(),
            'metadata'    => $metadata,
            'created_at'  => now(),
        ]);
    }
}
