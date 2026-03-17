<?php

namespace App\Http\Controllers\Dosen;

use App\Domain\Thesis\Enums\ThesisStatus;
use App\Domain\Thesis\Services\ThesisFileService;
use App\Domain\Thesis\Services\ThesisGuidanceService;
use App\Domain\Thesis\Services\ThesisWorkflowService;
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\ThesisGuidance;
use App\Models\ThesisRevision;
use App\Models\ThesisSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    public function __construct(
        private ThesisGuidanceService $guidanceService,
        private ThesisWorkflowService $workflow,
        private ThesisFileService     $fileService,
    ) {}

    private function getDosen(): Dosen
    {
        return Dosen::where('user_id', Auth::id())->firstOrFail();
    }

    // ── Dashboard: Daftar Mahasiswa Bimbingan ─────────────────────────────

    public function index()
    {
        $dosen = $this->getDosen();
        // Pending supervisor requests where this dosen was requested
        $pendingRequests = ThesisSubmission::where('requested_supervisor_id', $dosen->id)
            ->where('status', ThesisStatus::PROPOSAL_PENDING_SUPERVISOR)
            ->with('mahasiswa.user')
            ->orderByDesc('updated_at')
            ->get();

        $bimbingans = ThesisSubmission::where(function($q) use ($dosen) {
                // Active bimbingan (already approved by admin)
                $q->where('approved_supervisor_id', $dosen->id)
                  ->whereIn('status', [
                      ThesisStatus::BIMBINGAN_ACTIVE,
                      ThesisStatus::ELIGIBLE_SIDANG,
                      ThesisStatus::SIDANG_REG_DRAFT,
                      ThesisStatus::SIDANG_REG_SUBMITTED,
                      ThesisStatus::SIDANG_SCHEDULED,
                      ThesisStatus::SIDANG_COMPLETED,
                      ThesisStatus::REVISION_PENDING,
                      ThesisStatus::REVISION_UPLOADED,
                      ThesisStatus::REVISION_APPROVED,
                      ThesisStatus::THESIS_COMPLETED,
                  ]);
            })
            ->orWhere(function($q) use ($dosen) {
                // Proposals accepted by lecturer but pending admin review
                $q->where('requested_supervisor_id', $dosen->id)
                  ->where('status', ThesisStatus::PROPOSAL_SUBMITTED);
            })
            ->with(['mahasiswa.user', 'guidances'])
            ->orderByDesc('updated_at')
            ->get();

        // Daftar sidang yang diuji (penguji 1 atau 2)
        $sidangs = ThesisSubmission::where('status', '>=', ThesisStatus::SIDANG_SCHEDULED->value)
            ->whereHas('sidangSchedule', fn($q) =>
                $q->where('penguji_1_id', $dosen->id)
                  ->orWhere('penguji_2_id', $dosen->id)
                  ->orWhere('pembimbing_id', $dosen->id)
            )
            ->with(['mahasiswa.user', 'sidangSchedule', 'sidangRegistration.files'])
            ->get();

        return view('dosen.thesis.index', compact('dosen', 'bimbingans', 'sidangs', 'pendingRequests'));
    }

    // ── Detail Mahasiswa Bimbingan ─────────────────────────────────────────

    public function show(ThesisSubmission $thesis)
    {
        $dosen = $this->getDosen();

        // Dosen harus sebagai pembimbing atau penguji
        // Dosen is supervisor if they are approved OR if they are requested and proposal is submitted
        $isSupervisor = ($thesis->approved_supervisor_id === $dosen->id) || 
                       ($thesis->requested_supervisor_id === $dosen->id && $thesis->status === ThesisStatus::PROPOSAL_SUBMITTED);
        
        $isPenguji    = optional($thesis->sidangSchedule)->penguji_1_id === $dosen->id
            || optional($thesis->sidangSchedule)->penguji_2_id === $dosen->id;

        abort_unless($isSupervisor || $isPenguji, 403);

        $thesis->load([
            'mahasiswa.user',
            'guidances',
            'sidangRegistration.files',
            'sidangSchedule',
            'revisions',
        ]);

        return view('dosen.thesis.show', compact('thesis', 'dosen', 'isSupervisor'));
    }

    // ── Review Bimbingan ──────────────────────────────────────────────────

    public function approveGuidance(Request $request, ThesisGuidance $guidance)
    {
        $dosen = $this->getDosen();
        abort_unless($guidance->dosen_id === $dosen->id, 403);

        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->guidanceService->approve($guidance, $dosen, $request->note);

        \App\Models\AuditLog::log('thesis.guidance_approved', $guidance, [
            'mahasiswa_id' => $guidance->submission->mahasiswa_id,
            'note' => $request->note
        ]);

        return back()->with('success', 'Bimbingan disetujui.');
    }

    public function rejectGuidance(Request $request, ThesisGuidance $guidance)
    {
        $dosen = $this->getDosen();
        abort_unless($guidance->dosen_id === $dosen->id, 403);

        $request->validate(['note' => ['required', 'string', 'max:1000']]);
        $this->guidanceService->reject($guidance, $dosen, $request->note);

        return back()->with('success', 'Bimbingan dikembalikan untuk diperbaiki.');
    }

    // ── ACC Revisi ────────────────────────────────────────────────────────

    public function approveRevision(Request $request, ThesisRevision $revision)
    {
        $dosen      = $this->getDosen();
        $submission = $revision->submission;

        abort_unless($submission->approved_supervisor_id === $dosen->id, 403);
        abort_unless($submission->status === ThesisStatus::REVISION_UPLOADED, 403);

        $request->validate(['notes' => ['nullable', 'string', 'max:2000']]);

        $revision->update(['dosen_notes' => $request->notes]);
        $this->workflow->approveRevision($submission, $dosen);

        \App\Models\AuditLog::log('thesis.revision_approved', $submission, [
            'revision_id' => $revision->id,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Revisi di-ACC. Skripsi mahasiswa dinyatakan selesai.');
    }

    // ── Supervisor request (accept / reject) ─────────────────────────────────

    public function acceptSupervisor(Request $request, ThesisSubmission $thesis)
    {
        $dosen = $this->getDosen();

        // Only allow if this dosen was requested
        abort_unless($thesis->requested_supervisor_id === $dosen->id, 403);

        $this->workflow->supervisorAcceptProposal($thesis, $dosen);

        \App\Models\AuditLog::log('thesis.supervisor_accepted', $thesis, [
            'mahasiswa_id' => $thesis->mahasiswa_id
        ]);

        return redirect()->route('dosen.thesis.show', $thesis->id)
            ->with('success', 'Permintaan pembimbing diterima. Proposal dikirim ke admin untuk direview.');
    }

    public function rejectSupervisor(Request $request, ThesisSubmission $thesis)
    {
        $dosen = $this->getDosen();

        abort_unless($thesis->requested_supervisor_id === $dosen->id, 403);

        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);

        $this->workflow->supervisorRejectProposal($thesis, $dosen, $request->note ?? null);

        \App\Models\AuditLog::log('thesis.supervisor_rejected', $thesis, [
            'mahasiswa_id' => $thesis->mahasiswa_id,
            'note' => $request->note
        ]);

        return redirect()->route('dosen.thesis.index')
            ->with('success', 'Permintaan pembimbing ditolak. Mahasiswa akan diberi tahu untuk memilih dosen lain.');
    }

    // ── Download file skripsi / PPT ───────────────────────────────────────

    public function downloadFile(string $encodedPath, ThesisSubmission $thesis)
    {
        $dosen = $this->getDosen();

        $isSupervisor = $thesis->approved_supervisor_id === $dosen->id;
        $isPenguji    = optional($thesis->sidangSchedule)->penguji_1_id === $dosen->id
            || optional($thesis->sidangSchedule)->penguji_2_id === $dosen->id;

        abort_unless(
            ($isSupervisor || $isPenguji)
            && in_array($thesis->status, [
                ThesisStatus::SIDANG_SCHEDULED,
                ThesisStatus::SIDANG_COMPLETED,
                ThesisStatus::REVISION_PENDING,
                ThesisStatus::REVISION_UPLOADED,
                ThesisStatus::REVISION_APPROVED,
                ThesisStatus::THESIS_COMPLETED,
            ], true),
            403, 'File belum tersedia.'
        );

        $path = base64_decode($encodedPath);
        abort_unless($path && str_starts_with($path, 'skripsi/'), 403);

        return $this->fileService->downloadResponse($path, basename($path));
    }
}
