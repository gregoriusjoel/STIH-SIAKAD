<?php

namespace App\Http\Controllers\Dosen;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Skripsi\Services\SkripsiFileService;
use App\Domain\Skripsi\Services\SkripsiGuidanceService;
use App\Domain\Skripsi\Services\SkripsiWorkflowService;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\SkripsiGuidance;
use App\Models\SkripsiRevision;
use App\Models\SkripsiSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkripsiController extends Controller
{
    public function __construct(
        private SkripsiGuidanceService $guidanceService,
        private SkripsiWorkflowService $workflow,
        private SkripsiFileService     $fileService,
    ) {}

    private function getDosen(): Dosen
    {
        return Dosen::where('user_id', Auth::id())->firstOrFail();
    }

    // ── Dashboard: Daftar Mahasiswa Bimbingan ─────────────────────────────

    public function index()
    {
        $dosen = $this->getDosen();
        $pendingRequests = SkripsiSubmission::where('requested_supervisor_id', $dosen->id)
            ->where('status', SkripsiStatus::PROPOSAL_PENDING_SUPERVISOR)
            ->with('mahasiswa.user')
            ->orderByDesc('updated_at')
            ->get();

        $bimbingans = SkripsiSubmission::where(function($q) use ($dosen) {
                $q->where('approved_supervisor_id', $dosen->id)
                  ->whereIn('status', [
                      SkripsiStatus::BIMBINGAN_ACTIVE,
                      SkripsiStatus::ELIGIBLE_SIDANG,
                      SkripsiStatus::SIDANG_REG_DRAFT,
                      SkripsiStatus::SIDANG_REG_SUBMITTED,
                      SkripsiStatus::SIDANG_SCHEDULED,
                      SkripsiStatus::SIDANG_COMPLETED,
                      SkripsiStatus::REVISION_PENDING,
                      SkripsiStatus::REVISION_UPLOADED,
                      SkripsiStatus::REVISION_APPROVED,
                      SkripsiStatus::SKRIPSI_COMPLETED,
                  ]);
            })
            ->orWhere(function($q) use ($dosen) {
                $q->where('requested_supervisor_id', $dosen->id)
                  ->where('status', SkripsiStatus::PROPOSAL_SUBMITTED);
            })
            ->with(['mahasiswa.user', 'guidances'])
            ->orderByDesc('updated_at')
            ->get();

        $sidangs = SkripsiSubmission::where('status', '>=', SkripsiStatus::SIDANG_SCHEDULED->value)
            ->whereHas('sidangSchedule', fn($q) =>
                $q->where('penguji_1_id', $dosen->id)
                  ->orWhere('penguji_2_id', $dosen->id)
                  ->orWhere('pembimbing_id', $dosen->id)
            )
            ->with(['mahasiswa.user', 'sidangSchedule', 'sidangRegistration.files'])
            ->get();

        return view('dosen.skripsi.index', compact('dosen', 'bimbingans', 'sidangs', 'pendingRequests'));
    }

    // ── Detail Mahasiswa Bimbingan ─────────────────────────────────────────

    public function show(SkripsiSubmission $skripsi)
    {
        $dosen = $this->getDosen();

        $isSupervisor = ($skripsi->approved_supervisor_id === $dosen->id) ||
                       ($skripsi->requested_supervisor_id === $dosen->id && $skripsi->status === SkripsiStatus::PROPOSAL_SUBMITTED);

        $isPenguji    = optional($skripsi->sidangSchedule)->penguji_1_id === $dosen->id
            || optional($skripsi->sidangSchedule)->penguji_2_id === $dosen->id;

        abort_unless($isSupervisor || $isPenguji, 403);

        $skripsi->load([
            'mahasiswa.user',
            'guidances',
            'sidangRegistration.files',
            'sidangSchedule',
            'revisions',
        ]);

        return view('dosen.skripsi.show', compact('skripsi', 'dosen', 'isSupervisor'));
    }

    // ── Review Bimbingan ──────────────────────────────────────────────────

    public function approveGuidance(Request $request, SkripsiGuidance $guidance)
    {
        $dosen = $this->getDosen();
        abort_unless($guidance->dosen_id === $dosen->id, 403);

        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->guidanceService->approve($guidance, $dosen, $request->note);

        \App\Models\AuditLog::log('skripsi.guidance_approved', $guidance, [
            'mahasiswa_id' => $guidance->submission->mahasiswa_id,
            'note' => $request->note
        ]);

        return back()->with('success', 'Bimbingan disetujui.');
    }

    public function rejectGuidance(Request $request, SkripsiGuidance $guidance)
    {
        $dosen = $this->getDosen();
        abort_unless($guidance->dosen_id === $dosen->id, 403);

        $request->validate(['note' => ['required', 'string', 'max:1000']]);
        $this->guidanceService->reject($guidance, $dosen, $request->note);

        return back()->with('success', 'Bimbingan dikembalikan untuk diperbaiki.');
    }

    // ── ACC Revisi ────────────────────────────────────────────────────────

    public function approveRevision(Request $request, SkripsiRevision $revision)
    {
        $dosen      = $this->getDosen();
        $submission = $revision->submission;

        abort_unless($submission->approved_supervisor_id === $dosen->id, 403);
        abort_unless($submission->status === SkripsiStatus::REVISION_UPLOADED, 403);

        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $revision->update(['dosen_notes' => $request->notes]);
        $this->workflow->approveRevision($submission, $dosen);

        \App\Models\AuditLog::log('skripsi.revision_approved', $submission, [
            'revision_id' => $revision->id,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Revisi di-ACC. Skripsi mahasiswa dinyatakan selesai.');
    }

    // ── Supervisor request (accept / reject) ─────────────────────────────────

    public function acceptSupervisor(Request $request, SkripsiSubmission $skripsi)
    {
        $dosen = $this->getDosen();
        abort_unless($skripsi->requested_supervisor_id === $dosen->id, 403);

        $this->workflow->supervisorAcceptProposal($skripsi, $dosen);

        \App\Models\AuditLog::log('skripsi.supervisor_accepted', $skripsi, [
            'mahasiswa_id' => $skripsi->mahasiswa_id
        ]);

        return redirect()->route('dosen.skripsi.show', $skripsi->id)
            ->with('success', 'Permintaan pembimbing diterima. Skripsi dikirim ke admin untuk direview.');
    }

    public function rejectSupervisor(Request $request, SkripsiSubmission $skripsi)
    {
        $dosen = $this->getDosen();
        abort_unless($skripsi->requested_supervisor_id === $dosen->id, 403);

        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);

        $this->workflow->supervisorRejectProposal($skripsi, $dosen, $request->note ?? null);

        \App\Models\AuditLog::log('skripsi.supervisor_rejected', $skripsi, [
            'mahasiswa_id' => $skripsi->mahasiswa_id,
            'note' => $request->note
        ]);

        return redirect()->route('dosen.skripsi.index')
            ->with('success', 'Permintaan pembimbing ditolak. Mahasiswa akan diberi tahu untuk memilih dosen lain.');
    }

    // ── Download file skripsi / PPT ───────────────────────────────────────

    public function downloadFile(SkripsiSubmission $skripsi, string $encodedPath)
    {
        $dosen = $this->getDosen();

        $isSupervisor = $skripsi->approved_supervisor_id === $dosen->id;
        $isPenguji    = optional($skripsi->sidangSchedule)->penguji_1_id === $dosen->id
            || optional($skripsi->sidangSchedule)->penguji_2_id === $dosen->id;

        abort_unless(
            ($isSupervisor || $isPenguji)
            && in_array($skripsi->status, [
                SkripsiStatus::SIDANG_SCHEDULED,
                SkripsiStatus::SIDANG_COMPLETED,
                SkripsiStatus::REVISION_PENDING,
                SkripsiStatus::REVISION_UPLOADED,
                SkripsiStatus::REVISION_APPROVED,
                SkripsiStatus::SKRIPSI_COMPLETED,
            ], true),
            403, 'File belum tersedia.'
        );

        $path = base64_decode($encodedPath);
        abort_unless($path && str_starts_with($path, 'skripsi/'), 403);

        return $this->fileService->downloadResponse($path, basename($path));
    }
}
