<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Wisuda\Enums\WisudaRegistrationStatus;
use App\Domain\Wisuda\Services\WisudaFileService;
use App\Domain\Wisuda\Services\WisudaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wisuda\AssignBatchRequest;
use App\Http\Requests\Wisuda\StoreBatchRequest;
use App\Models\WisudaBatch;
use App\Models\WisudaRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WisudaController extends Controller
{
    public function __construct(
        private WisudaService $wisudaService,
        private WisudaFileService $fileService,
    ) {}

    // ── Registration List ───────────────────────────────────────────────

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = WisudaRegistration::with(['mahasiswa.user', 'skripsiSubmission', 'batch', 'reviewer'])
            ->orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $registrations = $query->paginate(20)->withQueryString();

        $counts = [
            'pending'   => WisudaRegistration::where('status', 'pending')->count(),
            'approved'  => WisudaRegistration::where('status', 'approved')->count(),
            'scheduled' => WisudaRegistration::where('status', 'scheduled')->count(),
            'rejected'  => WisudaRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.wisuda.index', compact('registrations', 'status', 'counts'));
    }

    // ── Registration Detail ─────────────────────────────────────────────

    public function show($id)
    {
        $registration = WisudaRegistration::with([
            'mahasiswa.user',
            'mahasiswa.prodiData',
            'skripsiSubmission',
            'batch',
            'reviewer',
            'documents',
        ])->findOrFail($id);

        return view('admin.wisuda.show', compact('registration'));
    }

    // ── Approve / Reject ────────────────────────────────────────────────

    public function approve($id)
    {
        $reg = WisudaRegistration::findOrFail($id);

        if ($reg->status !== WisudaRegistrationStatus::PENDING) {
            return back()->with('error', 'Hanya registrasi berstatus pending yang bisa di-approve.');
        }

        $this->wisudaService->approve($reg, Auth::user());

        return back()->with('success', 'Registrasi wisuda berhasil di-approve.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_note' => ['required', 'string', 'max:2000'],
        ]);

        $reg = WisudaRegistration::findOrFail($id);

        if ($reg->status !== WisudaRegistrationStatus::PENDING) {
            return back()->with('error', 'Hanya registrasi berstatus pending yang bisa ditolak.');
        }

        $this->wisudaService->reject($reg, Auth::user(), $request->input('rejection_note'));

        return back()->with('success', 'Registrasi wisuda telah ditolak.');
    }

    // ── Batch List ──────────────────────────────────────────────────────

    public function batches()
    {
        $batches = WisudaBatch::withCount('registrations')
            ->orderByDesc('tanggal')
            ->paginate(20);

        $approvedCount = WisudaRegistration::where('status', 'approved')
            ->whereNull('wisuda_batch_id')
            ->count();

        return view('admin.wisuda.batches', compact('batches', 'approvedCount'));
    }

    // ── Create Batch ────────────────────────────────────────────────────

    public function createBatch()
    {
        return view('admin.wisuda.batch-create');
    }

    public function storeBatch(StoreBatchRequest $request)
    {
        $batch = $this->wisudaService->createBatch($request->validated(), Auth::user());

        return redirect()->route('admin.wisuda.batches.show', $batch->id)
            ->with('success', 'Batch wisuda berhasil dibuat.');
    }

    // ── Batch Detail ────────────────────────────────────────────────────

    public function showBatch($id)
    {
        $batch = WisudaBatch::with([
            'registrations.mahasiswa.user',
            'registrations.mahasiswa.prodiData',
            'creator',
        ])->findOrFail($id);

        // Approved registrations not yet assigned to any batch
        $availableRegistrations = WisudaRegistration::where('status', 'approved')
            ->whereNull('wisuda_batch_id')
            ->with(['mahasiswa.user', 'mahasiswa.prodiData'])
            ->orderByDesc('reviewed_at')
            ->get();

        return view('admin.wisuda.batch-show', compact('batch', 'availableRegistrations'));
    }

    // ── Assign to Batch ─────────────────────────────────────────────────

    public function assignToBatch(AssignBatchRequest $request, $batchId)
    {
        $batch = WisudaBatch::findOrFail($batchId);
        $this->wisudaService->assignToBatch($batch, $request->input('registration_ids'));

        return back()->with('success', 'Mahasiswa berhasil di-assign ke batch dan notifikasi email dikirim.');
    }

    // ── Update Batch ────────────────────────────────────────────────────

    public function updateBatch(Request $request, $batchId)
    {
        $request->validate([
            'tanggal'     => ['sometimes', 'date'],
            'waktu_mulai' => ['sometimes', 'date_format:H:i'],
            'lokasi'      => ['sometimes', 'string', 'max:255'],
            'catatan'     => ['nullable', 'string', 'max:2000'],
        ]);

        $batch = WisudaBatch::findOrFail($batchId);
        $this->wisudaService->updateBatch($batch, $request->all());

        return back()->with('success', 'Batch wisuda berhasil diperbarui dan notifikasi ulang dikirim.');
    }

    // ── File Download / Preview ─────────────────────────────────────────

    public function downloadFile($encodedPath)
    {
        $path = base64_decode($encodedPath);
        return $this->fileService->downloadResponse($path, basename($path));
    }

    public function previewFile($encodedPath)
    {
        $path = base64_decode($encodedPath);
        return $this->fileService->previewResponse($path);
    }
}
