<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Thesis\Enums\ThesisStatus;
use App\Domain\Thesis\Services\ThesisFileService;
use App\Domain\Thesis\Services\ThesisSidangService;
use App\Domain\Thesis\Services\ThesisWorkflowService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Thesis\ScheduleSidangRequest;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\ThesisSidangRegistration;
use App\Models\ThesisSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    public function __construct(
        private ThesisWorkflowService $workflow,
        private ThesisSidangService   $sidangService,
        private ThesisFileService     $fileService,
    ) {}

    private function admin(): Admin
    {
        return Admin::where('user_id', Auth::id())->firstOrFail();
    }

    // ── Dashboard ─────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'proposal');

        $proposals = ThesisSubmission::whereIn('status', [
                ThesisStatus::PROPOSAL_SUBMITTED,
                ThesisStatus::PROPOSAL_APPROVED,
                ThesisStatus::PROPOSAL_REJECTED,
            ])
            ->with('mahasiswa.user', 'requestedSupervisor')
            ->latest()
            ->get();

        $activeBimbingan = ThesisSubmission::whereIn('status', [
                ThesisStatus::BIMBINGAN_ACTIVE,
                ThesisStatus::ELIGIBLE_SIDANG,
            ])
            ->with('mahasiswa.user', 'approvedSupervisor')
            ->latest()
            ->get();

        $pendingSidang = ThesisSidangRegistration::where('status', 'submitted')
            ->with('submission.mahasiswa.user')
            ->latest()
            ->get();

        $scheduled = ThesisSubmission::where('status', ThesisStatus::SIDANG_SCHEDULED)
            ->with('mahasiswa.user', 'sidangSchedule.pembimbing', 'sidangSchedule.penguji1')
            ->latest()
            ->get();

        $revisions = ThesisSubmission::where('status', ThesisStatus::REVISION_UPLOADED)
            ->with('mahasiswa.user', 'latestRevision')
            ->latest()
            ->get();

        $completed = ThesisSubmission::where('status', ThesisStatus::THESIS_COMPLETED)
            ->with('mahasiswa.user')
            ->latest()
            ->get();

        return view('admin.thesis.index', compact(
            'tab', 'proposals', 'activeBimbingan', 'pendingSidang', 'scheduled', 'revisions', 'completed'
        ));
    }

    // ── Show Detail ───────────────────────────────────────────────────────

    public function show(ThesisSubmission $thesis)
    {
        $thesis->load([
            'mahasiswa.user',
            'requestedSupervisor.user',
            'approvedSupervisor.user',
            'guidances.dosen',
            'sidangRegistration.files',
            'sidangSchedule.pembimbing',
            'sidangSchedule.penguji1',
            'sidangSchedule.penguji2',
            'sidangSchedule.ruangan',
            'revisions.approvedBy',
        ]);

        return view('admin.thesis.show', compact('thesis'));
    }

    // ── Proposal Actions ──────────────────────────────────────────────────

    public function approveProposal(Request $request, ThesisSubmission $thesis)
    {
        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->workflow->approveProposal($thesis, $this->admin(), $request->note);

        return back()->with('success', 'Proposal disetujui. Bimbingan aktif.');
    }

    public function rejectProposal(Request $request, ThesisSubmission $thesis)
    {
        $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $this->workflow->rejectProposal($thesis, $this->admin(), $request->reason);

        return back()->with('success', 'Proposal ditolak. Mahasiswa dapat mengajukan ulang.');
    }

    // ── Sidang Registration Actions ───────────────────────────────────────

    public function verifySidangRegistration(Request $request, ThesisSidangRegistration $reg)
    {
        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->workflow->verifySidangRegistration($reg, $this->admin(), $request->note);

        return back()->with('success', 'Pendaftaran sidang terverifikasi. Silakan tentukan jadwal.');
    }

    public function rejectSidangRegistration(Request $request, ThesisSidangRegistration $reg)
    {
        $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $this->workflow->rejectSidangRegistration($reg, $this->admin(), $request->reason);

        return back()->with('success', 'Pendaftaran sidang ditolak.');
    }

    // ── Schedule Sidang ───────────────────────────────────────────────────

    public function scheduleForm(ThesisSubmission $thesis)
    {
        $thesis->load(['sidangRegistration.files', 'mahasiswa.user']);
        // Order dosen by related user name so DB handles sorting
        $dosens   = Dosen::select('dosens.*')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->get();
        $ruangans = Ruangan::where('status', 'aktif')->orWhereNull('status')->orderBy('nama_ruangan')->get();

        return view('admin.thesis.schedule', compact('thesis', 'dosens', 'ruangans'));
    }

    public function storeSidangSchedule(ScheduleSidangRequest $request, ThesisSubmission $thesis)
    {
        $this->sidangService->schedule($thesis, $request->validated(), $this->admin());

        return redirect()->route('admin.thesis.show', $thesis)
            ->with('success', 'Jadwal sidang berhasil ditetapkan.');
    }

    // ── Mark Sidang Completed ─────────────────────────────────────────────

    public function completeSidang(ThesisSubmission $thesis)
    {
        $this->workflow->completeSidang($thesis);

        return back()->with('success', 'Sidang ditandai selesai. Mahasiswa masuk masa revisi.');
    }

    // ── File Download ─────────────────────────────────────────────────────

    public function downloadFile(string $path)
    {
        // path is base64-encoded to avoid URL issues
        $decoded = base64_decode($path);
        abort_unless($decoded && str_starts_with($decoded, 'skripsi/'), 403);

        return $this->fileService->downloadResponse($decoded, basename($decoded));
    }
}
