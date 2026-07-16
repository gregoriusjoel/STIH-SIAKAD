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
        $student = auth()->user()->mahasiswa;

        if (!$student) {
            abort(403, 'Profil mahasiswa tidak ditemukan');
        }

        // Get new system invoices
        $invoices = Invoice::where('student_id', $student->id)
            ->with(['installments', 'paymentProofs'])
            ->latest()
            ->get();

        // Get existing payment data from old system (deprecated - return empty)
        $existingPayments = collect();

        // Check for upcoming payments (H-7)
        $upcomingPayment = null;
        foreach ($invoices as $invoice) {
            if ($invoice->status === 'LUNAS') continue;

            foreach ($invoice->installments as $inst) {
                if ($inst->status === 'UNPAID' && $inst->due_date) {
                    $diff = now()->diffInDays($inst->due_date, false);
                    if ($diff >= 0 && $diff <= 7) {
                        $upcomingPayment = $inst;
                        break 2;
                    }
                }
            }
        }

        return view('page.mahasiswa.pembayaran.invoices.index', compact('invoices', 'existingPayments', 'upcomingPayment'));
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

        $pendingFullProof = $invoice->paymentProofs()
            ->whereNull('installment_id')
            ->where('status', 'UPLOADED')
            ->latest()
            ->first();

        return view('page.mahasiswa.pembayaran.invoices.show', compact('invoice', 'pendingFullProof'));
    }

    /**
     * Show form to request installment
     */
    public function createInstallmentRequest(Invoice $invoice)
    {
        $student = auth()->user()->mahasiswa;

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
        $student = auth()->user()->mahasiswa;

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
        $student = auth()->user()->mahasiswa;
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
     * Show form to upload full payment proof (without installment)
     */
    public function createFullPaymentProof(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $student = auth()->user()->mahasiswa;

        if (!$student || $invoice->student_id !== $student->id) {
            abort(403);
        }

        if ($invoice->status === 'LUNAS' || $invoice->isFullyPaid()) {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas');
        }

        if ($invoice->status !== 'PUBLISHED') {
            return redirect()->back()->with('error', 'Bayar penuh hanya tersedia untuk tagihan baru');
        }

        if ($invoice->installmentRequest()->whereIn('status', ['SUBMITTED', 'APPROVED'])->exists()) {
            return redirect()->back()->with('error', 'Tagihan ini sedang/ sudah diproses sebagai cicilan');
        }

        if ($invoice->paymentProofs()->whereNull('installment_id')->where('status', 'UPLOADED')->exists()) {
            return redirect()->back()->with('error', 'Bukti bayar penuh sudah diupload dan sedang diverifikasi');
        }

        $dueAmount = max(0, (int) $invoice->total_tagihan - (int) $invoice->total_paid);
        if ($dueAmount <= 0) {
            return redirect()->back()->with('error', 'Tagihan ini sudah lunas');
        }

        return view('page.mahasiswa.pembayaran.payment-proofs.create-full', compact('invoice', 'dueAmount'));
    }

    /**
     * Store payment proof
     */
    public function storePaymentProof(StorePaymentProofRequest $request)
    {
        $student = auth()->user()->mahasiswa;
        $installment = null;
        $invoice = null;

        if ($request->filled('installment_id')) {
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

            // Guard from bypassing create form checks
            if ($installment->status === 'WAITING_VERIFICATION') {
                return redirect()->back()->with('error', 'Bukti bayar sudah diupload dan sedang diverifikasi');
            }

            if ($installment->status === 'PAID') {
                return redirect()->back()->with('error', 'Cicilan sudah dibayar');
            }
        } else {
            $invoice = Invoice::findOrFail($request->invoice_id);

            if ($invoice->student_id !== $student->id) {
                abort(403);
            }

            if ($invoice->status === 'LUNAS' || $invoice->isFullyPaid()) {
                return redirect()->back()->with('error', 'Tagihan ini sudah lunas');
            }

            if ($invoice->status !== 'PUBLISHED') {
                return redirect()->back()->with('error', 'Bayar penuh hanya tersedia untuk tagihan baru');
            }

            if ($invoice->installmentRequest()->whereIn('status', ['SUBMITTED', 'APPROVED'])->exists()) {
                return redirect()->back()->with('error', 'Tagihan ini sedang/ sudah diproses sebagai cicilan');
            }

            if ($invoice->paymentProofs()->whereNull('installment_id')->where('status', 'UPLOADED')->exists()) {
                return redirect()->back()->with('error', 'Bukti bayar penuh sudah diupload dan sedang diverifikasi');
            }

            $dueAmount = max(0, (int) $invoice->total_tagihan - (int) $invoice->total_paid);
            if ((int) $request->amount_submitted !== $dueAmount) {
                return redirect()->back()->with('error', 'Nominal bayar penuh harus sama dengan sisa tagihan');
            }
        }

        // Store file on S3
        $file       = $request->file('file');
        $filePath   = $this->storage->upload($file, 'documents/payment-proofs');

        // Create proof
        PaymentProof::create([
            'invoice_id' => $invoice->id,
            'installment_id' => $installment?->id,
            'uploaded_by' => auth()->id(),
            'transfer_date' => $request->input('transfer_date'),
            'amount_submitted' => $request->input('amount_submitted'),
            'method' => $request->input('method'),
            'file_path' => $filePath,
            'student_notes' => $request->input('student_notes'),
            'status' => 'UPLOADED',
        ]);

        // Update installment status
        if ($installment) {
            $installment->update([
                'status' => 'WAITING_VERIFICATION',
            ]);
        }

        return redirect()
            ->route('mahasiswa.invoices.show', $invoice)
            ->with('success', 'Bukti bayar berhasil diupload dan menunggu verifikasi');
    }

    /**
     * Show payment history
     */
    public function paymentHistory()
    {
        $student = auth()->user()->mahasiswa;

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
