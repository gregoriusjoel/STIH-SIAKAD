<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Domain\Wisuda\Enums\WisudaDocumentType;
use App\Domain\Wisuda\Services\WisudaEligibilityService;
use App\Domain\Wisuda\Services\WisudaService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wisuda\StoreWisudaRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WisudaController extends Controller
{
    public function __construct(
        private WisudaEligibilityService $eligibility,
        private WisudaService $wisudaService,
    ) {}

    private function getMahasiswa()
    {
        return Auth::user()->mahasiswa ?? abort(403);
    }

    // ── Index / Status Tracker ──────────────────────────────────────────

    public function index()
    {
        $mahasiswa = $this->getMahasiswa();
        $summary   = $this->eligibility->getSummary($mahasiswa);

        return view('page.mahasiswa.wisuda.index', compact('mahasiswa', 'summary'));
    }

    // ── Registration Form ───────────────────────────────────────────────

    public function registerForm()
    {
        $mahasiswa = $this->getMahasiswa();

        $unpaidSemesters = $this->eligibility->getUnpaidSemesters($mahasiswa);
        if (!empty($unpaidSemesters)) {
            return redirect()->route('mahasiswa.wisuda.index')
                ->with('error', 'Anda belum melunasi pembayaran uang kuliah pada semester: ' . implode(', ', $unpaidSemesters));
        }

        if (! $this->eligibility->isEligible($mahasiswa)) {
            return redirect()->route('mahasiswa.wisuda.index')
                ->with('error', 'Anda belum memenuhi syarat untuk mendaftar wisuda.');
        }

        $submission    = $this->eligibility->getEligibleSubmission($mahasiswa);
        $documentTypes = WisudaDocumentType::cases();

        return view('page.mahasiswa.wisuda.register', compact(
            'mahasiswa', 'submission', 'documentTypes'
        ));
    }

    // ── Store Registration ──────────────────────────────────────────────

    public function store(StoreWisudaRegistrationRequest $request)
    {
        $mahasiswa  = $this->getMahasiswa();

        $unpaidSemesters = $this->eligibility->getUnpaidSemesters($mahasiswa);
        if (!empty($unpaidSemesters)) {
            return redirect()->route('mahasiswa.wisuda.index')
                ->with('error', 'Pendaftaran gagal. Anda belum melunasi pembayaran uang kuliah pada semester: ' . implode(', ', $unpaidSemesters));
        }

        $submission = $this->eligibility->getEligibleSubmission($mahasiswa);

        if (! $submission || $this->eligibility->hasActiveRegistration($mahasiswa)) {
            return redirect()->route('mahasiswa.wisuda.index')
                ->with('error', 'Anda tidak bisa mendaftar wisuda saat ini.');
        }

        $reg = $this->wisudaService->createRegistration(
            $mahasiswa,
            $submission->id,
            $request->input('no_hp'),
            $request->input('email_aktif'),
        );

        return redirect()->route('mahasiswa.wisuda.index')
            ->with('success', 'Pendaftaran wisuda berhasil dibuat. Silakan upload dokumen yang diperlukan.');
    }

    // ── Upload Document ─────────────────────────────────────────────────

    public function uploadDocument(Request $request, $regId)
    {
        $mahasiswa = $this->getMahasiswa();
        $reg       = $mahasiswa->wisudaRegistrations()->findOrFail($regId);

        $request->validate([
            'file_type' => ['required', 'in:' . implode(',', WisudaDocumentType::allValues())],
            'file'      => ['required', 'file', 'max:5120'], // 5MB
        ]);

        $type = $request->input('file_type');
        $docType = WisudaDocumentType::from($type);

        // Extra MIME validation based on type
        $allowedMimes = $docType->acceptedMimes();
        $request->validate([
            'file' => ['mimes:' . $allowedMimes],
        ]);

        $this->wisudaService->upsertDocument($reg, $type, $request->file('file'), $mahasiswa);

        return back()->with('success', $docType->label() . ' berhasil diupload.');
    }

    // ── Submit Registration ─────────────────────────────────────────────

    public function submit($regId)
    {
        $mahasiswa = $this->getMahasiswa();
        $reg       = $mahasiswa->wisudaRegistrations()
            ->with('documents')
            ->findOrFail($regId);

        if (! $reg->hasRequiredDocuments()) {
            return back()->with('error', 'Semua dokumen wajib diupload sebelum submit.');
        }

        $reg->update([
            'submitted_at' => now(),
        ]);

        return redirect()->route('mahasiswa.wisuda.index')
            ->with('success', 'Pendaftaran wisuda telah disubmit dan menunggu verifikasi admin.');
    }

    public function printCard()
    {
        $mahasiswa = $this->getMahasiswa();
        $reg = $mahasiswa->wisudaRegistrations()
            ->where('status', 'scheduled')
            ->with('batch')
            ->firstOrFail();

        return view('page.mahasiswa.wisuda.print-card', compact('mahasiswa', 'reg'));
    }
}
