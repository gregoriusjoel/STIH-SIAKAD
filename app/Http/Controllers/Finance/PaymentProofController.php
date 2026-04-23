<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewPaymentProofRequest;
use App\Models\PaymentProof;
use App\Services\ApprovePaymentProofService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentProofController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ApprovePaymentProofService $approveService
    ) {}

    /**
     * Show uploaded payment proofs
     */
    public function index()
    {
        $proofs = PaymentProof::with([
                'invoice.student.user',
                'installment',
                'uploader'
            ])
            ->where('status', 'UPLOADED')
            ->orderBy('created_at')
            ->paginate(10);

        return view('finance.payment-proofs.index', compact('proofs'));
    }

    /**
     * Show payment proof detail
     */
    public function show(PaymentProof $paymentProof)
    {
        $paymentProof->load([
            'invoice.student.user',
            'installment',
            'uploader',
            'approver'
        ]);

        return view('finance.payment-proofs.show', compact('paymentProof'));
    }

    /**
     * Review (approve/reject) payment proof
     */
    public function review(ReviewPaymentProofRequest $request, PaymentProof $paymentProof)
    {
        $this->authorize('review', $paymentProof);

        try {
            if ($request->action === 'approve') {
                $this->approveService->approve($paymentProof, $request->notes);
                $message = 'Bukti bayar berhasil diverifikasi';
            } else {
                $this->approveService->reject($paymentProof, $request->notes);
                $message = 'Bukti bayar ditolak';
            }

            return redirect()
                ->route('finance.payment-proofs.show', $paymentProof)
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
