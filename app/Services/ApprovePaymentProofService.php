<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\PaymentProof;
use Illuminate\Support\Facades\DB;

class ApprovePaymentProofService
{
    /**
     * Approve payment proof and create payment record
     */
    public function approve(PaymentProof $proof, ?string $notes = null): void
    {
        DB::transaction(function () use ($proof, $notes) {
            // Lock the proof to prevent concurrent approval
            $proof = PaymentProof::where('id', $proof->id)
                ->lockForUpdate()
                ->first();

            // Guard: check if already approved
            if ($proof->status !== 'UPLOADED') {
                throw new \Exception('Proof already processed');
            }

            // Guard: check if proof already has payment
            if ($proof->payment()->exists()) {
                throw new \Exception('Payment already exists for this proof');
            }

            $invoice = $proof->invoice;
            $installment = $proof->installment;

            // Validate amount
            if ($installment) {
                if (!$invoice->allow_partial && $proof->amount_submitted !== $installment->amount) {
                    throw new \Exception('Amount must match installment amount');
                }
            }

            // Update proof status
            $proof->update([
                'status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'finance_notes' => $notes,
            ]);

            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'installment_id' => $installment?->id,
                'proof_id' => $proof->id,
                'amount_approved' => $proof->amount_submitted,
                'paid_date' => now()->toDateString(), // Tanggal approve = tanggal bayar resmi
                'transfer_date' => $proof->transfer_date,
                'approved_by' => auth()->id(),
            ]);

            // Update installment status if exists
            if ($installment) {
                $installment->update([
                    'status' => 'PAID',
                    'paid_at' => now(),
                ]);

                // Check if all installments are paid
                if ($invoice->allInstallmentsPaid()) {
                    $invoice->update([
                        'status' => 'LUNAS',
                    ]);

                    // Audit log for invoice completion
                    AuditLog::log('invoice.completed', $invoice, [
                        'payment_id' => $payment->id,
                    ]);
                }
            } else {
                // Full payment without installment
                if ($invoice->isFullyPaid()) {
                    $invoice->update([
                        'status' => 'LUNAS',
                    ]);

                    AuditLog::log('invoice.completed', $invoice, [
                        'payment_id' => $payment->id,
                    ]);
                }
            }

            // Audit log
            AuditLog::log('payment_proof.approve', $proof, [
                'payment_id' => $payment->id,
                'installment_id' => $installment?->id,
                'amount' => $proof->amount_submitted,
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Reject payment proof
     */
    public function reject(PaymentProof $proof, string $reason): void
    {
        DB::transaction(function () use ($proof, $reason) {
            // Lock the proof
            $proof = PaymentProof::where('id', $proof->id)
                ->lockForUpdate()
                ->first();

            // Guard: check if already processed
            if ($proof->status !== 'UPLOADED') {
                throw new \Exception('Proof already processed');
            }

            // Update proof status
            $proof->update([
                'status' => 'REJECTED',
                'approved_by' => auth()->id(),
                'rejected_at' => now(),
                'finance_notes' => $reason,
            ]);

            // Update installment status if exists
            if ($proof->installment) {
                $proof->installment->update([
                    'status' => 'REJECTED_PAYMENT',
                ]);
            }

            // Audit log
            AuditLog::log('payment_proof.reject', $proof, [
                'reason' => $reason,
                'installment_id' => $proof->installment_id,
            ]);
        });
    }
}
