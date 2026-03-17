<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Domain\Thesis\Enums\SidangFileType;
use App\Domain\Thesis\Enums\ThesisStatus;
use App\Domain\Thesis\Services\ThesisEligibilityService;
use App\Domain\Thesis\Services\ThesisFileService;
use App\Domain\Thesis\Services\ThesisGuidanceService;
use App\Domain\Thesis\Services\ThesisSidangService;
use App\Domain\Thesis\Services\ThesisWorkflowService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Thesis\StoreGuidanceRequest;
use App\Http\Requests\Thesis\SubmitProposalRequest;
use App\Models\Dosen;
use App\Models\ThesisSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    public function __construct(
        private ThesisEligibilityService $eligibility,
        private ThesisWorkflowService    $workflow,
        private ThesisFileService        $fileService,
        private ThesisGuidanceService    $guidanceService,
        private ThesisSidangService      $sidangService,
    ) {}

    private function getMahasiswa()
    {
        return Auth::user()->mahasiswa ?? abort(403);
    }

    // ── Dashboard / Tracker ───────────────────────────────────────────────

    public function index()
    {
        $mahasiswa  = $this->getMahasiswa();
        $summary    = $this->eligibility->getSummary($mahasiswa);
        $submission = $summary['submission'];

        return view('page.mahasiswa.thesis.index', compact('mahasiswa', 'summary', 'submission'));
    }

    // ── Proposal ──────────────────────────────────────────────────────────

    public function proposalForm()
    {
        $mahasiswa = $this->getMahasiswa();

        if (! $this->eligibility->isSkripsiEligible($mahasiswa)) {
            return redirect()->route('mahasiswa.thesis.index')
                ->with('error', 'Anda belum memenuhi syarat minimal 120 SKS.');
        }

        $submission = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->first();
        // Order by related user name (dosens.user_id -> users.id)
        $dosens     = Dosen::select('dosens.*')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->get();

        return view('page.mahasiswa.thesis.proposal', compact('mahasiswa', 'submission', 'dosens'));
    }

    public function submitProposal(SubmitProposalRequest $request)
    {
        $mahasiswa = $this->getMahasiswa();

        if (! $this->eligibility->isSkripsiEligible($mahasiswa)) {
            abort(403, 'Belum memenuhi syarat SKS.');
        }

        // Get or create submission
        $submission = ThesisSubmission::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id, 'status' => ThesisStatus::PROPOSAL_DRAFT],
            [
                'judul'                   => $request->judul,
                'deskripsi_proposal'      => $request->deskripsi_proposal,
                'requested_supervisor_id' => $request->requested_supervisor_id,
            ]
        );

        // Update if already exists in draft/rejected
        if (! $submission->wasRecentlyCreated) {
            $submission->update([
                'judul'                   => $request->judul,
                'deskripsi_proposal'      => $request->deskripsi_proposal,
                'requested_supervisor_id' => $request->requested_supervisor_id,
                'status'                  => ThesisStatus::PROPOSAL_DRAFT,
            ]);
        }

        // Handle file upload
        if ($request->hasFile('proposal_file')) {
            $path = $this->fileService->storeProposal($mahasiswa, $request->file('proposal_file'));
            $submission->update(['proposal_file_path' => $path]);
        }

        // Submit to admin
        $this->workflow->submitProposal($submission);

        \App\Models\AuditLog::log('thesis.proposal_submitted', $submission, [
            'judul' => $submission->judul,
            'supervisor_id' => $submission->requested_supervisor_id
        ]);

        return redirect()->route('mahasiswa.thesis.index')
            ->with('success', 'Proposal berhasil diajukan. Menunggu konfirmasi dosen pembimbing.');
    }

    // ── Bimbingan ─────────────────────────────────────────────────────────

    public function bimbingan()
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless(
            in_array($submission->status, [
                ThesisStatus::BIMBINGAN_ACTIVE,
                ThesisStatus::ELIGIBLE_SIDANG,
                ThesisStatus::SIDANG_REG_DRAFT,
                ThesisStatus::SIDANG_REG_REJECTED,
            ], true),
            403, 'Bimbingan belum tersedia.'
        );

        $guidances = $submission->guidances()->orderByDesc('tanggal_bimbingan')->get();

        return view('page.mahasiswa.thesis.bimbingan', compact('mahasiswa', 'submission', 'guidances'));
    }

    public function storeGuidance(StoreGuidanceRequest $request)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        $filePath = null;
        if ($request->hasFile('file_bimbingan')) {
            $filePath = $this->fileService->storeGuidanceFile($mahasiswa, $request->file('file_bimbingan'));
        }

        $this->guidanceService->create($submission, array_merge(
            $request->validated(),
            ['file_path' => $filePath]
        ));

        return back()->with('success', 'Data bimbingan berhasil ditambahkan.');
    }

    // ── Sidang Registration ───────────────────────────────────────────────

    public function sidangRegistration()
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless($this->eligibility->isSidangEligible($submission), 403, 'Bimbingan minimal 8x belum terpenuhi.');

        $reg       = $this->sidangService->initRegistration($submission);
        $fileTypes = SidangFileType::cases();
        $uploaded  = $reg->files->keyBy('file_type');

        return view('page.mahasiswa.thesis.sidang_registration',
            compact('mahasiswa', 'submission', 'reg', 'fileTypes', 'uploaded')
        );
    }

    public function uploadSidangFile(Request $request, int $regId)
    {
        $mahasiswa = $this->getMahasiswa();
        $reg       = \App\Models\ThesisSidangRegistration::findOrFail($regId);

        abort_unless($reg->submission->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless(in_array($reg->status, ['draft', 'rejected']), 403, 'Tidak bisa mengubah file.');

        $request->validate([
            'file_type' => ['required', 'in:' . implode(',', SidangFileType::allValues())],
            'file'      => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,jpg,png', 'max:20480'],
        ]);

        $this->sidangService->upsertFile($reg, $request->file_type, $request->file('file'), $mahasiswa);

        return back()->with('success', 'File berhasil diupload.');
    }

    public function submitSidangRegistration(int $regId)
    {
        $mahasiswa = $this->getMahasiswa();
        $reg       = \App\Models\ThesisSidangRegistration::findOrFail($regId);

        abort_unless($reg->submission->mahasiswa_id === $mahasiswa->id, 403);

        try {
            $this->sidangService->submit($reg);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('mahasiswa.thesis.index')
            ->with('success', 'Pendaftaran sidang berhasil dikumpulkan. Menunggu verifikasi admin.');
    }

    // ── Revision ──────────────────────────────────────────────────────────

    public function uploadRevision(Request $request)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = ThesisSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless($submission->status === ThesisStatus::REVISION_PENDING, 403);

        $request->validate([
            'revision_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
            'notes'         => ['nullable', 'string', 'max:2000'],
        ]);

        $path = $this->fileService->storeRevision($mahasiswa, $request->file('revision_file'));

        $submission->revisions()->create([
            'revision_file_path' => $path,
            'original_name'      => $request->file('revision_file')->getClientOriginalName(),
            'notes'              => $request->notes,
            'uploaded_at'        => now(),
        ]);

        $this->workflow->uploadRevision($submission);

        \App\Models\AuditLog::log('thesis.revision_uploaded', $submission, [
            'notes' => $request->notes
        ]);

        return redirect()->route('mahasiswa.thesis.index')
            ->with('success', 'File revisi berhasil diupload. Menunggu ACC dari dosen pembimbing.');
    }

    // ── Download (private) ────────────────────────────────────────────────

    public function downloadFile(string $type, int $submissionId)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = ThesisSubmission::findOrFail($submissionId);

        abort_unless($submission->mahasiswa_id === $mahasiswa->id, 403);

        $path = match ($type) {
            'proposal' => $submission->proposal_file_path,
            default    => null,
        };

        abort_unless($path, 404);

        return $this->fileService->downloadResponse($path, basename($path));
    }
}
