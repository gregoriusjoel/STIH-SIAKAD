<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Semester;
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

        $invoices = $query->paginate(10);

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

        $activeSemester = Semester::where('status', 'aktif')
            ->orderByDesc('tanggal_mulai')
            ->first()
            ?? Semester::where('is_active', true)->orderByDesc('tanggal_mulai')->first()
            ?? Semester::orderByDesc('tanggal_mulai')->first();

        $students = Mahasiswa::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(fn($s) => $s->user->name)
            ->values();

        // Build SKS map by student + kode_id in one query, then derive defaults per student.
        $krsSksMap = [];
        $krsSksRows = Krs::query()
            ->selectRaw('krs.mahasiswa_id, mata_kuliahs.kode_id, SUM(COALESCE(mata_kuliahs.sks, 0)) as total_sks')
            ->join('mata_kuliahs', 'mata_kuliahs.id', '=', 'krs.mata_kuliah_id')
            ->where('krs.status', '!=', 'draft')
            ->whereNotNull('mata_kuliahs.kode_id')
            ->groupBy('krs.mahasiswa_id', 'mata_kuliahs.kode_id')
            ->get();

        foreach ($krsSksRows as $row) {
            $krsSksMap[$row->mahasiswa_id][$row->kode_id] = (int) $row->total_sks;
        }

        $studentDefaults = [];
        foreach ($students as $student) {
            $currentSemester = (int) $student->getCurrentSemester();
            $kodeId = 'sms' . $currentSemester;
            $defaultSks = (int) ($krsSksMap[$student->id][$kodeId] ?? 0);

            $studentDefaults[$student->id] = [
                'semester' => $currentSemester,
                'tahun_ajaran' => $activeSemester?->tahun_ajaran ?? '',
                'sks_ambil' => $defaultSks,
                'paket_sks_bayar' => $defaultSks,
            ];
        }

        return view('finance.invoices.create', compact('students', 'studentDefaults', 'activeSemester'));
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
            'status' => 'DRAFT',
            'auto_generated_from_krs' => false,
            'allow_partial' => $request->allow_partial ?? false,
            'notes' => $request->notes,
            'bank_name' => $request->bank_name,
            'va_number' => $request->va_number,
            'created_by' => auth()->id(),
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
        $invoice->load(['student.user', 'installments', 'installmentRequest', 'payments.proof']);

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
     * Show form to edit invoice (for confirming/editing draft invoices)
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        // Only allow editing DRAFT invoices
        if ($invoice->status !== 'DRAFT') {
            return redirect()->route('finance.invoices.show', $invoice)
                ->with('error', 'Hanya tagihan dengan status DRAFT yang dapat diedit.');
        }

        $activeSemester = Semester::where('status', 'aktif')
            ->orderByDesc('tanggal_mulai')
            ->first()
            ?? Semester::where('is_active', true)->orderByDesc('tanggal_mulai')->first()
            ?? Semester::orderByDesc('tanggal_mulai')->first();

        $students = Mahasiswa::with('user')
            ->whereHas('user')
            ->get()
            ->sortBy(fn($s) => $s->user->name)
            ->values();

        $studentDefaults = [];
        foreach ($students as $student) {
            $currentSemester = (int) $student->getCurrentSemester();
            $studentDefaults[$student->id] = [
                'semester' => $currentSemester,
                'tahun_ajaran' => $activeSemester?->tahun_ajaran ?? '',
            ];
        }

        return view('finance.invoices.edit', compact('invoice', 'students', 'studentDefaults', 'activeSemester'));
    }

    /**
     * Update invoice
     */
    public function update(StoreInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        // Only allow updating DRAFT invoices
        if ($invoice->status !== 'DRAFT') {
            return redirect()->route('finance.invoices.show', $invoice)
                ->with('error', 'Hanya tagihan dengan status DRAFT yang dapat diubah.');
        }

        // If invoice is auto-generated from KRS, don't allow changing student/semester/tahun_ajaran/sks fields
        $updateData = [
            'total_tagihan' => $request->total_tagihan,
            'allow_partial' => $request->allow_partial ?? false,
            'notes' => $request->notes,
            'bank_name' => $request->bank_name,
            'va_number' => $request->va_number,
        ];

        // Only allow changing student/semester fields if NOT auto-generated from KRS
        if (!$invoice->auto_generated_from_krs) {
            $updateData = array_merge($updateData, [
                'student_id' => $request->student_id,
                'semester' => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran,
                'sks_ambil' => $request->sks_ambil,
                'paket_sks_bayar' => $request->paket_sks_bayar,
            ]);
        }

        $invoice->update($updateData);

        return redirect()
            ->route('finance.invoices.show', $invoice)
            ->with('success', 'Tagihan berhasil diperbarui');
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
