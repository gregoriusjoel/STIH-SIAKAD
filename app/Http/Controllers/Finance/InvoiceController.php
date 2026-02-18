<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show invoices list for finance
     */
    public function index()
    {
        $status = request('status');
        
        $query = Invoice::with(['student.user'])
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        $invoices = $query->paginate(20);

        // Calculate stats for the dashboard
        $stats = [
            'total' => Invoice::count(),
            'published' => Invoice::where('status', 'PUBLISHED')->count(),
            'paid' => Invoice::where('status', 'LUNAS')->count(),
            'in_installment' => Invoice::where('status', 'IN_INSTALLMENT')->count(),
            'total_amount' => Invoice::where('status', '!=', 'CANCELLED')->sum('total_tagihan'),
        ];

        return view('finance.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Show form to create invoice
     */
    public function create()
    {
        $this->authorize('create', Invoice::class);

        $students = Mahasiswa::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(fn($s) => $s->user->name);

        return view('finance.invoices.create', compact('students'));
    }

    /**
     * Store invoice
     */
    public function store(StoreInvoiceRequest $request)
    {
        $invoice = Invoice::create([
            'student_id' => $request->student_id,
            'semester' => $request->semester,
            'tahun_ajaran' => $request->tahun_ajaran,
            'sks_ambil' => $request->sks_ambil,
            'paket_sks_bayar' => $request->paket_sks_bayar,
            'total_tagihan' => $request->total_tagihan,
            'allow_partial' => $request->allow_partial ?? false,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
            'status' => 'DRAFT',
        ]);

        return redirect()
            ->route('finance.invoices.show', $invoice)
            ->with('success', 'Tagihan berhasil dibuat');
    }

    /**
     * Show invoice detail
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['student.user', 'installments', 'installmentRequest', 'payments']);

        return view('finance.invoices.show', compact('invoice'));
    }

    /**
     * Publish invoice
     */
    public function publish(Invoice $invoice)
    {
        $this->authorize('publish', $invoice);

        $invoice->update([
            'status' => 'PUBLISHED',
            'published_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Tagihan berhasil dipublish');
    }

    /**
     * Cancel invoice
     */
    public function cancel(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice->update([
            'status' => 'CANCELLED',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Tagihan berhasil dibatalkan');
    }
}
