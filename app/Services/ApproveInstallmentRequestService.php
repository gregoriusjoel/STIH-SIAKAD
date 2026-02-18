<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Installment;
use App\Models\InstallmentRequest;
use Illuminate\Support\Facades\DB;

class ApproveInstallmentRequestService
{
    /**
     * Approve installment request and generate installments
     */
    public function approve(
        InstallmentRequest $request, 
        int $approvedTerms, 
        ?string $notes = null
    ): void {
        DB::transaction(function () use ($request, $approvedTerms, $notes) {
            // Lock the request to prevent concurrent approval
            $request = InstallmentRequest::where('id', $request->id)
                ->lockForUpdate()
                ->first();

            // Guard: check if already approved
            if ($request->status !== 'SUBMITTED') {
                throw new \Exception('Request already processed');
            }

            $invoice = $request->invoice;

            // Guard: check invoice status
            if ($invoice->status !== 'PUBLISHED') {
                throw new \Exception('Invoice must be PUBLISHED');
            }

            // Update request
            $request->update([
                'approved_terms' => $approvedTerms,
                'status' => 'APPROVED',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Calculate installments
            $installmentsData = $this->calculateInstallments(
                $invoice->total_tagihan,
                $approvedTerms
            );

            // Create installment records
            foreach ($installmentsData as $data) {
                Installment::create([
                    'invoice_id' => $invoice->id,
                    'installment_no' => $data['no'],
                    'amount' => $data['amount'],
                    'due_date' => now()->addMonths($data['no'] - 1),
                    'status' => 'UNPAID',
                ]);
            }

            // Update invoice status
            $invoice->update([
                'status' => 'IN_INSTALLMENT',
            ]);

            // Audit log
            AuditLog::log('installment_request.approve', $request, [
                'approved_terms' => $approvedTerms,
                'invoice_id' => $invoice->id,
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Reject installment request
     */
    public function reject(InstallmentRequest $request, string $reason): void
    {
        DB::transaction(function () use ($request, $reason) {
            // Lock the request
            $request = InstallmentRequest::where('id', $request->id)
                ->lockForUpdate()
                ->first();

            // Guard: check if already processed
            if ($request->status !== 'SUBMITTED') {
                throw new \Exception('Request already processed');
            }

            // Update request
            $request->update([
                'status' => 'REJECTED',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Audit log
            AuditLog::log('installment_request.reject', $request, [
                'reason' => $reason,
            ]);
        });
    }

    /**
     * Calculate installments with rounding
     */
    protected function calculateInstallments(int $totalTagihan, int $terms): array
    {
        $rounding = 1000;
        $baseCicilan = floor($totalTagihan / $terms);
        $cicilanBulat = floor($baseCicilan / $rounding) * $rounding;

        $installments = [];
        
        // First n-1 installments
        for ($i = 1; $i < $terms; $i++) {
            $installments[] = [
                'no' => $i,
                'amount' => $cicilanBulat,
            ];
        }

        // Last installment (absorbs the difference)
        $sisaCicilan = ($terms - 1) * $cicilanBulat;
        $cicilanTerakhir = $totalTagihan - $sisaCicilan;

        $installments[] = [
            'no' => $terms,
            'amount' => $cicilanTerakhir,
        ];

        return $installments;
    }
}
