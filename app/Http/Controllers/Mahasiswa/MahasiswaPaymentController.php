<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstallmentRequest;
use App\Http\Requests\StorePaymentProofRequest;
use App\Models\Installment;
use App\Models\InstallmentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentProof;
use App\Models\Pembayaran;
use App\Services\FileStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MahasiswaPaymentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private FileStorageService $storage) {}

    /**
     * Show mahasiswa invoices and existing payments
     */
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Student profile not found');
        }

        // Get new invoices from payment system
        $invoices = Invoice::where('student_id', $student->id)
            ->whereIn('status', ['PUBLISHED', 'IN_INSTALLMENT', 'LUNAS'])
            ->with(['installments', 'installmentRequest'])
            ->orderByDesc('tahun_ajaran')
            ->orderByDesc('semester')
            ->get();

        // Get existing payments from old system
        $existingPayments = Pembayaran::where('mahasiswa_id', $student->id)
            ->with('semester')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('page.mahasiswa.pembayaran.invoices.index', compact('invoices', 'existingPayments'));
    }

    /**
     * Show invoice detail with installments
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load([
            'installments.paymentProofs' => function ($query) {
                $query->latest();
            },
            'installmentRequest',
            'student',
        ]);

        return view('page.mahasiswa.pembayaran.invoices.show', compact('invoice'));
    }

    /**
     * Show form to request installment
     */
    public function createInstallmentRequest(Invoice $invoice)
    {
        $student = auth()->user()->student;

        if (!$student || $invoice->student_id !== $student->id) {
            abort(403);
        }

        if ($invoice->status !== 'PUBLISHED') {
            return redirect()->back()->with('error', 'Tagihan tidak dapat diajukan cicilan');
        }

        // Check if already has pending/approved request
        if ($invoice->installmentRequest()->whereIn('status', ['SUBMITTED', 'APPROVED'])->exists()) {
            return redirect()->back()->with('error', 'Sudah ada pengajuan cicilan untuk tagihan ini');
        }

        return view('page.mahasiswa.pembayaran.installment-requests.create', compact('invoice'));
    }

    /**
     * Store installment request
     */
    public function storeInstallmentRequest(StoreInstallmentRequest $request, Invoice $invoice)
    {
        $student = auth()->user()->student;

        InstallmentRequest::create([
            'invoice_id' => $invoice->id,
            'student_id' => $student->id,
            'requested_terms' => $request->requested_terms,
            'alasan' => $request->alasan,
            'status' => 'SUBMITTED',
        ]);

        return redirect()
            ->route('mahasiswa.invoices.show', $invoice)
            ->with('success', 'Pengajuan cicilan berhasil dikirim');
    }

    /**
     * Show form to upload payment proof
     */
    public function createPaymentProof(Installment $installment)
    {
        $student = auth()->user()->student;
        $invoice = $installment->invoice;

        if (!$student || $invoice->student_id !== $student->id) {
            abort(403);
        }

        // Check if can be paid (previous installment must be paid)
        if (!$installment->canBePaid()) {
            return redirect()->back()->with('error', 'Cicilan sebelumnya harus dibayar terlebih dahulu');
        }

        // Check if already has uploaded proof
        if ($installment->status === 'WAITING_VERIFICATION') {
            return redirect()->back()->with('error', 'Bukti bayar sudah diupload dan sedang diverifikasi');
        }

        if ($installment->status === 'PAID') {
            return redirect()->back()->with('error', 'Cicilan sudah dibayar');
        }

        return view('page.mahasiswa.pembayaran.payment-proofs.create', compact('installment', 'invoice'));
    }

    /**
     * Store payment proof
     */
    public function storePaymentProof(StorePaymentProofRequest $request)
    {
        $student = auth()->user()->student;
        $installment = Installment::findOrFail($request->installment_id);
        $invoice = $installment->invoice;

        // Double check ownership
        if ($invoice->student_id !== $student->id) {
            abort(403);
        }

        // Check if can be paid
        if (!$installment->canBePaid()) {
            return redirect()->back()->with('error', 'Cicilan sebelumnya harus dibayar terlebih dahulu');
        }

        // Store file on S3
        $file       = $request->file('file');
        $filePath   = $this->storage->upload($file, 'documents/payment-proofs');

        // Create proof
        PaymentProof::create([
            'invoice_id' => $invoice->id,
            'installment_id' => $installment->id,
            'uploaded_by' => auth()->id(),
            'transfer_date' => $request->transfer_date,
            'amount_submitted' => $request->amount_submitted,
            'method' => $request->method,
            'file_path' => $filePath,
            'student_notes' => $request->student_notes,
            'status' => 'UPLOADED',
        ]);

        // Update installment status
        $installment->update([
            'status' => 'WAITING_VERIFICATION',
        ]);

        return redirect()
            ->route('mahasiswa.invoices.show', $invoice)
            ->with('success', 'Bukti bayar berhasil diupload dan menunggu verifikasi');
    }

    /**
     * Show payment history
     */
    public function paymentHistory()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403);
        }

        $payments = Payment::whereHas('invoice', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->with(['invoice', 'installment', 'proof'])
            ->orderByDesc('paid_date')
            ->get();

        return view('page.mahasiswa.pembayaran.payments.history', compact('payments'));
    }
}
