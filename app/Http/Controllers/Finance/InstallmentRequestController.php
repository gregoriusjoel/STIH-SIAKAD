<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveInstallmentRequestRequest;
use App\Http\Requests\RejectInstallmentRequestRequest;
use App\Models\InstallmentRequest;
use App\Services\ApproveInstallmentRequestService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InstallmentRequestController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ApproveInstallmentRequestService $approveService
    ) {}

    /**
     * Show pending installment requests
     */
    public function index()
    {
        $requests = InstallmentRequest::with(['student.user', 'invoice'])
            ->where('status', 'SUBMITTED')
            ->orderBy('created_at')
            ->paginate(10);

        return view('finance.installment-requests.index', compact('requests'));
    }

    /**
     * Show installment request detail
     */
    public function show(InstallmentRequest $installmentRequest)
    {
        $installmentRequest->load(['student.user', 'invoice', 'reviewer']);

        return view('finance.installment-requests.show', compact('installmentRequest'));
    }

    /**
     * Approve installment request
     */
    public function approve(
        ApproveInstallmentRequestRequest $request, 
        InstallmentRequest $installmentRequest
    ) {
        $this->authorize('review', $installmentRequest);

        try {
            $this->approveService->approve(
                $installmentRequest,
                $request->approved_terms,
                $request->notes
            );

            return redirect()
                ->route('finance.installment-requests.show', $installmentRequest)
                ->with('success', 'Pengajuan cicilan berhasil disetujui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Reject installment request
     */
    public function reject(
        RejectInstallmentRequestRequest $request, 
        InstallmentRequest $installmentRequest
    ) {
        $this->authorize('review', $installmentRequest);

        try {
            $this->approveService->reject(
                $installmentRequest,
                $request->rejection_reason
            );

            return redirect()
                ->route('finance.installment-requests.show', $installmentRequest)
                ->with('success', 'Pengajuan cicilan ditolak');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }
}
