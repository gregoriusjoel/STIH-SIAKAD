<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Domain\Skripsi\Enums\SidangFileType;
use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Skripsi\Services\SkripsiEligibilityService;
use App\Domain\Skripsi\Services\SkripsiFileService;
use App\Domain\Skripsi\Services\SkripsiGuidanceService;
use App\Domain\Skripsi\Services\SkripsiSidangService;
use App\Domain\Skripsi\Services\SkripsiWorkflowService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Skripsi\StoreGuidanceRequest;
use App\Http\Requests\Skripsi\SubmitProposalRequest;
use App\Models\Dosen;
use App\Models\SkripsiSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkripsiController extends Controller
{
    public function __construct(
        private SkripsiEligibilityService $eligibility,
        private SkripsiWorkflowService    $workflow,
        private SkripsiFileService        $fileService,
        private SkripsiGuidanceService    $guidanceService,
        private SkripsiSidangService      $sidangService,
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

        return view('page.mahasiswa.skripsi.index', compact('mahasiswa', 'summary', 'submission'));
    }

    // ── Proposal ──────────────────────────────────────────────────────────

    public function proposalForm()
    {
        $mahasiswa = $this->getMahasiswa();

        if (! $this->eligibility->isSkripsiEligible($mahasiswa)) {
            return redirect()->route('mahasiswa.skripsi.index')
                ->with('error', 'Anda belum memenuhi syarat minimal 120 SKS.');
        }

        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->first();
        $dosens     = Dosen::select('dosens.*')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->get();

        return view('page.mahasiswa.skripsi.proposal', compact('mahasiswa', 'submission', 'dosens'));
    }

    public function submitProposal(SubmitProposalRequest $request)
    {
        $mahasiswa = $this->getMahasiswa();

        if (! $this->eligibility->isSkripsiEligible($mahasiswa)) {
            abort(403, 'Belum memenuhi syarat SKS.');
        }

        $submission = SkripsiSubmission::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id, 'status' => SkripsiStatus::PROPOSAL_DRAFT],
            [
                'judul'                   => $request->judul,
                'deskripsi_proposal'      => $request->deskripsi_proposal,
                'requested_supervisor_id' => $request->requested_supervisor_id,
            ]
        );

        if (! $submission->wasRecentlyCreated) {
            $submission->update([
                'judul'                   => $request->judul,
                'deskripsi_proposal'      => $request->deskripsi_proposal,
                'requested_supervisor_id' => $request->requested_supervisor_id,
                'status'                  => SkripsiStatus::PROPOSAL_DRAFT,
            ]);
        }

        if ($request->hasFile('proposal_file')) {
            $path = $this->fileService->storeProposal($mahasiswa, $request->file('proposal_file'));
            $submission->update(['proposal_file_path' => $path]);
        }

        $this->workflow->submitProposal($submission);

        \App\Models\AuditLog::log('skripsi.proposal_submitted', $submission, [
            'judul' => $submission->judul,
            'supervisor_id' => $submission->requested_supervisor_id
        ]);

        return redirect()->route('mahasiswa.skripsi.index')
            ->with('success', 'Skripsi berhasil diajukan. Menunggu konfirmasi dosen pembimbing.');
    }

    // ── Bimbingan ─────────────────────────────────────────────────────────

    public function bimbingan()
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless(
            in_array($submission->status, [
                SkripsiStatus::BIMBINGAN_ACTIVE,
                SkripsiStatus::ELIGIBLE_SIDANG,
                SkripsiStatus::SIDANG_REG_DRAFT,
                SkripsiStatus::SIDANG_REG_REJECTED,
            ], true),
            403, 'Bimbingan belum tersedia.'
        );

        $guidances = $submission->guidances()->orderByDesc('tanggal_bimbingan')->get();

        return view('page.mahasiswa.skripsi.bimbingan', compact('mahasiswa', 'submission', 'guidances'));
    }

    public function storeGuidance(StoreGuidanceRequest $request)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

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

    // ── Logbook Template & Upload ─────────────────────────────────────────

    public function downloadLogbookTemplate()
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless(
            in_array($submission->status, [
                SkripsiStatus::BIMBINGAN_ACTIVE,
                SkripsiStatus::ELIGIBLE_SIDANG,
                SkripsiStatus::SIDANG_REG_DRAFT,
                SkripsiStatus::SIDANG_REG_REJECTED,
            ], true),
            403, 'Template belum tersedia.'
        );

        $content  = $this->fileService->generateLogbookTemplate($submission);
        $filename = 'Logbook_Bimbingan_' . str_replace(' ', '_', $mahasiswa->user->name ?? 'Mahasiswa') . '.docx';

        return response($content, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function uploadLogbook(Request $request)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless(
            in_array($submission->status, [
                SkripsiStatus::BIMBINGAN_ACTIVE,
                SkripsiStatus::ELIGIBLE_SIDANG,
                SkripsiStatus::SIDANG_REG_DRAFT,
                SkripsiStatus::SIDANG_REG_REJECTED,
            ], true),
            403, 'Upload logbook tidak diizinkan pada status ini.'
        );

        $request->validate([
            'logbook' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'logbook.required' => 'File logbook wajib diupload.',
            'logbook.mimes'    => 'File harus dalam format PDF.',
            'logbook.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        if ($submission->logbook_file_path) {
            $this->fileService->delete($submission->logbook_file_path);
        }

        $path = $this->fileService->storeLogbook($mahasiswa, $request->file('logbook'));

        $submission->update([
            'logbook_file_path'    => $path,
            'logbook_original_name'=> $request->file('logbook')->getClientOriginalName(),
            'logbook_uploaded_at'  => now(),
        ]);

        $this->workflow->uploadLogbook($submission);

        return back()->with('success', 'Logbook bimbingan berhasil diupload.');
    }

    // ── Sidang Registration ───────────────────────────────────────────────

    public function sidangRegistration()
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless($this->eligibility->isSidangEligible($submission), 403, 'Bimbingan minimal 8x belum terpenuhi.');

        $reg       = $this->sidangService->initRegistration($submission);
        $fileTypes = SidangFileType::cases();
        $uploaded  = $reg->files->keyBy('file_type');

        return view('page.mahasiswa.skripsi.sidang_registration',
            compact('mahasiswa', 'submission', 'reg', 'fileTypes', 'uploaded')
        );
    }

    public function uploadSidangFile(Request $request, int $regId)
    {
        $mahasiswa = $this->getMahasiswa();
        $reg       = \App\Models\SkripsiSidangRegistration::findOrFail($regId);

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
        $reg       = \App\Models\SkripsiSidangRegistration::findOrFail($regId);

        abort_unless($reg->submission->mahasiswa_id === $mahasiswa->id, 403);

        try {
            $this->sidangService->submit($reg);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('mahasiswa.skripsi.index')
            ->with('success', 'Pendaftaran sidang berhasil dikumpulkan. Menunggu verifikasi admin.');
    }

    // ── Revision ──────────────────────────────────────────────────────────

    public function uploadRevision(Request $request)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->firstOrFail();

        abort_unless($submission->status === SkripsiStatus::REVISION_PENDING, 403);

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

        \App\Models\AuditLog::log('skripsi.revision_uploaded', $submission, [
            'notes' => $request->notes
        ]);

        return redirect()->route('mahasiswa.skripsi.index')
            ->with('success', 'File revisi berhasil diupload. Menunggu ACC dari dosen pembimbing.');
    }

    // ── Download (private) ────────────────────────────────────────────────

    public function downloadFile(string $type, int $submissionId)
    {
        $mahasiswa  = $this->getMahasiswa();
        $submission = SkripsiSubmission::findOrFail($submissionId);

        abort_unless($submission->mahasiswa_id === $mahasiswa->id, 403);

        $path = match ($type) {
            'proposal' => $submission->proposal_file_path,
            default    => null,
        };

        abort_unless($path, 404);

        return $this->fileService->downloadResponse($path, basename($path));
    }
}
