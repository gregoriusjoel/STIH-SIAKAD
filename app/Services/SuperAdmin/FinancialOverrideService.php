<?php

namespace App\Services\SuperAdmin;

use App\Models\Invoice;
use App\Models\Mahasiswa;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

/**
 * FinancialOverrideService
 * Handles invoice status overrides with mandatory payment record creation.
 */
class FinancialOverrideService
{
    /**
     * Override invoice status.
     * If new status is LUNAS, automatically creates a manual payment record.
     *
     * @param  Invoice  $invoice
     * @param  string   $newStatus  DRAFT|PUBLISHED|IN_INSTALLMENT|LUNAS
     * @param  string   $reason
     * @param  int|null $actorId
     * @return array
     */
    public function overrideStatus(Invoice $invoice, string $newStatus, string $reason, ?int $actorId = null): array
    {
        $allowedStatuses = ['DRAFT', 'PUBLISHED', 'IN_INSTALLMENT', 'LUNAS'];
        if (!in_array($newStatus, $allowedStatuses)) {
            throw new \InvalidArgumentException("Status tidak valid: {$newStatus}");
        }

        $before = $invoice->only(['status', 'notes']);
        $paymentCreated = null;

        DB::transaction(function () use ($invoice, $newStatus, $reason, $actorId, $before, &$paymentCreated) {
            $invoice->update([
                'status' => $newStatus,
                'notes'  => ($invoice->notes ? $invoice->notes . "\n" : '') .
                            "[OVERRIDE " . now()->format('d/m/Y H:i') . "] " . $reason,
            ]);

            // If overriding to LUNAS, create a manual payment record
            if ($newStatus === 'LUNAS') {
                $paymentCreated = $this->createManualPaymentRecord($invoice, $reason, $actorId);
            }

            AuditLog::log(
                action: 'invoice.override',
                auditable: $invoice,
                meta: [
                    'reason'            => $reason,
                    'student_id'        => $invoice->student_id,
                    'total_tagihan'     => $invoice->total_tagihan,
                    'payment_created'   => $paymentCreated?->id,
                    'actor_id'          => $actorId,
                ],
                before: $before,
                after: ['status' => $newStatus]
            );
        });

        return [
            'before'          => $before,
            'after'           => ['status' => $newStatus],
            'payment_created' => $paymentCreated,
        ];
    }

    /**
     * Create a manual payment record when overriding invoice to LUNAS.
     * This creates an audit trail entry in the payments table.
     */
    private function createManualPaymentRecord(Invoice $invoice, string $reason, ?int $actorId): object
    {
        $uploadedBy = $actorId ?? $invoice->student?->user_id ?? 1;
        $approvedBy = $actorId ?? 1;

        // 1. Insert into payment_proofs
        $proofId = DB::table('payment_proofs')->insertGetId([
            'invoice_id'       => $invoice->id,
            'installment_id'   => null,
            'uploaded_by'      => $uploadedBy,
            'transfer_date'    => now()->toDateString(),
            'amount_submitted' => $invoice->total_tagihan,
            'method'           => 'manual_override',
            'file_path'        => 'manual_override_proof.png',
            'status'           => 'APPROVED',
            'finance_notes'    => "Manual override oleh Super Admin: {$reason}",
            'approved_by'      => $approvedBy,
            'approved_at'      => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // 2. Insert into payments
        $id = DB::table('payments')->insertGetId([
            'invoice_id'      => $invoice->id,
            'installment_id'  => null,
            'proof_id'        => $proofId,
            'amount_approved' => $invoice->total_tagihan,
            'paid_date'       => now()->toDateString(),
            'transfer_date'   => now()->toDateString(),
            'approved_by'     => $approvedBy,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return (object) ['id' => $id];
    }
}
