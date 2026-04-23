<?php

namespace App\Services;

use App\Jobs\GenerateLetterJob;
use App\Models\Pengajuan;
use App\Models\PengajuanRevision;
use App\Support\LetterTemplateConfig;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanService
{
    /**
     * Step 1 – Buat draft pengajuan baru.
     */
    public function createDraft(int $mahasiswaId, string $jenis, string $keterangan, array $payload): Pengajuan
    {
        return Pengajuan::create([
            'mahasiswa_id'     => $mahasiswaId,
            'jenis'            => $jenis,
            'keterangan'       => $keterangan,
            'payload_template' => $payload,
            'status'           => Pengajuan::STATUS_DRAFT,
        ]);
    }

    /**
     * Step 2 – Dispatch job generate dokumen.
     * Mengembalikan true jika berhasil di-dispatch, false jika status tidak valid.
     */
    public function dispatchGenerate(Pengajuan $pengajuan): bool
    {
        if (!$pengajuan->canGenerateDoc()) {
            return false;
        }

        GenerateLetterJob::dispatch($pengajuan->id);
        return true;
    }

    /**
     * Step 4 – Upload signed document oleh mahasiswa.
     */
    public function uploadSignedDoc(Pengajuan $pengajuan, UploadedFile $file): void
    {
        // Hapus file lama jika ada
        if ($pengajuan->signed_doc_path) {
            Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->signed_doc_path))->delete($pengajuan->signed_doc_path);
        }

        $targetFolder = 'pengajuan/signed/' . $pengajuan->mahasiswa->storage_folder;
        $fileName = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $fileName);
        $path = $file->storeAs($targetFolder, $fileName, $resolvedDisk);

        $pengajuan->update(['signed_doc_path' => $path]);
    }

    /**
     * Step 5 – Submit ke admin (status → SUBMITTED).
     * Juga simpan history revisi jika ini adalah re-submit setelah reject.
     */
    public function submit(Pengajuan $pengajuan, ?string $revisionNote = null): void
    {
        DB::transaction(function () use ($pengajuan, $revisionNote) {
            // Jika ini revisi (setelah rejected), simpan history dulu
            if ($pengajuan->status === Pengajuan::STATUS_REJECTED) {
                PengajuanRevision::create([
                    'pengajuan_id'         => $pengajuan->id,
                    'revision_no'          => $pengajuan->revision_no,
                    'signed_doc_path'      => $pengajuan->signed_doc_path,
                    'note_from_admin'      => $pengajuan->rejected_reason,
                    'note_from_mahasiswa'  => $revisionNote,
                ]);

                $pengajuan->increment('revision_no');
            }

            $pengajuan->update([
                'status'       => Pengajuan::STATUS_SUBMITTED,
                'submitted_at' => now(),
                'rejected_reason' => null, // reset alasan penolakan
            ]);
        });
    }

    /**
     * Hapus pengajuan (mahasiswa): hapus file terkait dan model
     */
    public function deletePengajuan(Pengajuan $pengajuan): void
    {
        // Delete generated doc if exists
        if ($pengajuan->generated_doc_path) {
            Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->generated_doc_path))->delete($pengajuan->generated_doc_path);
        }

        // Delete signed doc if exists
        if ($pengajuan->signed_doc_path) {
            Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->signed_doc_path))->delete($pengajuan->signed_doc_path);
        }

        // Delete final file_surat if any
        if ($pengajuan->file_surat) {
            Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->file_surat))->delete($pengajuan->file_surat);
        }

        // Delete revisions
        if ($pengajuan->revisions()->exists()) {
            foreach ($pengajuan->revisions as $rev) {
                if ($rev->signed_doc_path) {
                    Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($rev->signed_doc_path))->delete($rev->signed_doc_path);
                }
                $rev->delete();
            }
        }

        // Finally delete the model
        $pengajuan->delete();
    }

    /**
     * Admin – Approve pengajuan (status → APPROVED).
     * Nomor surat di-generate otomatis.
     */
    public function approve(Pengajuan $pengajuan, int $approverId, ?string $adminNote = null): void
    {
        $nomorSurat = $this->generateNomorSurat($pengajuan);

        $pengajuan->update([
            'status'       => Pengajuan::STATUS_APPROVED,
            'approved_by'  => $approverId,
            'approved_at'  => now(),
            'admin_note'   => $adminNote,
            'nomor_surat'  => $nomorSurat,
        ]);
    }

    /**
     * Admin – Reject pengajuan (status → REJECTED).
     */
    public function reject(Pengajuan $pengajuan, int $approverId, string $reason): void
    {
        $pengajuan->update([
            'status'          => Pengajuan::STATUS_REJECTED,
            'approved_by'     => $approverId,
            'rejected_at'     => now(),
            'rejected_reason' => $reason,
            'admin_note'      => $reason,
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────

    private function generateNomorSurat(Pengajuan $pengajuan): string
    {
        $year  = date('Y');
        $month = date('m');
        $count = Pengajuan::where('status', Pengajuan::STATUS_APPROVED)
            ->whereYear('approved_at', $year)
            ->whereMonth('approved_at', $month)
            ->count() + 1;

        $prefix = match($pengajuan->jenis) {
            'cuti'           => 'CU',
            'dispensasi'     => 'DI',
            'izin_penelitian'=> 'IP',
            default          => 'SK',
        };

        return sprintf('%03d/STIH-ADH/%s/%s/%s', $count, $prefix, $month, $year);
    }
}
