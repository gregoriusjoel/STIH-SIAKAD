<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceFromKrsService
{
    /**
     * Generate or update invoice from submitted KRS
     * 
     * @param int $mahasiswaId The student ID
     * @return Invoice|null The created or existing invoice, or null if no KRS found
     * @throws \Exception
     */
    public function execute(int $mahasiswaId): ?Invoice
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);

        // Get student's current semester number
        $studentCurrentSemester = $mahasiswa->getCurrentSemester();

        // Get KRS records for this mahasiswa that are not draft
        // Status can be 'sudah submit' or 'approved'
        $krsList = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('status', '!=', 'draft')
            ->whereNotNull('mata_kuliah_id')
            ->with('mataKuliah')
            ->get();

        if ($krsList->isEmpty()) {
            \Log::info("GenerateInvoiceFromKrsService: No submitted KRS found for mahasiswa_id={$mahasiswaId}");
            return null;
        }

        // Get tahun_ajaran from the first KRS record (should be consistent for all KRS in this submission)
        $tahunAjaran = $krsList->first()->tahun_ajaran;

        if (!$tahunAjaran) {
            \Log::warning("GenerateInvoiceFromKrsService: No tahun_ajaran found in KRS records for mahasiswa_id={$mahasiswaId}");
            return null;
        }

        // Calculate total SKS from selected mata kuliah
        $totalSks = 0;
        foreach ($krsList as $krs) {
            if ($krs->mataKuliah) {
                $totalSks += (int) ($krs->mataKuliah->sks ?? 0);
            }
        }

        // Check if invoice already exists for this student + semester + tahun_ajaran
        $existingInvoice = Invoice::where('student_id', $mahasiswaId)
            ->where('semester', $studentCurrentSemester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();

        if ($existingInvoice) {
            // Update existing invoice if it's still in DRAFT status
            if ($existingInvoice->status === 'DRAFT') {
                $existingInvoice->update([
                    'sks_ambil' => $totalSks,
                    'paket_sks_bayar' => $totalSks,
                ]);
                \Log::info("GenerateInvoiceFromKrsService: Updated existing draft invoice #{$existingInvoice->id} for mahasiswa_id={$mahasiswaId}");
                return $existingInvoice;
            } else {
                // Invoice already published or in other status, don't modify
                \Log::info("GenerateInvoiceFromKrsService: Invoice #{$existingInvoice->id} exists with status={$existingInvoice->status}, skipping update for mahasiswa_id={$mahasiswaId}");
                return $existingInvoice;
            }
        }

        // Create new draft invoice
        $invoice = DB::transaction(function () use ($mahasiswaId, $studentCurrentSemester, $tahunAjaran, $totalSks) {
            $newInvoice = Invoice::create([
                'student_id' => $mahasiswaId,
                'semester' => $studentCurrentSemester,
                'tahun_ajaran' => $tahunAjaran,
                'sks_ambil' => $totalSks,
                'paket_sks_bayar' => $totalSks,
                'total_tagihan' => 0,  // Admin will set this after reviewing
                'status' => 'DRAFT',
                'auto_generated_from_krs' => true,
                'allow_partial' => false,
                'notes' => null,
                'bank_name' => null,
                'va_number' => null,
                'created_by' => auth()->id() ?? 1,  // System user or auth user
                'published_at' => null,
            ]);

            return $newInvoice;
        });

        \Log::info("GenerateInvoiceFromKrsService: Created new draft invoice #{$invoice->id} for mahasiswa_id={$mahasiswaId}, total_sks={$totalSks}");

        return $invoice;
    }

    /**
     * Check if invoice can be updated for changed KRS
     * Returns false if:
     * - Invoice status is not DRAFT
     * - Invoice has any payments recorded
     * 
     * @param Invoice $invoice
     * @return bool
     */
    public function canUpdateInvoice(Invoice $invoice): bool
    {
        // Only DRAFT invoices can be updated
        if ($invoice->status !== 'DRAFT') {
            return false;
        }

        // Check if invoice has any payments
        $hasPayments = $invoice->payments()->exists();

        return !$hasPayments;
    }
}
