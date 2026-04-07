<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Skripsi\Enums\SkripsiStatus;
use App\Domain\Skripsi\Services\SkripsiFileService;
use App\Domain\Skripsi\Services\SkripsiSidangService;
use App\Domain\Skripsi\Services\SkripsiWorkflowService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Skripsi\ScheduleSidangRequest;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\SkripsiSidangRegistration;
use App\Models\SkripsiSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkripsiController extends Controller
{
    public function __construct(
        private SkripsiWorkflowService $workflow,
        private SkripsiSidangService   $sidangService,
        private SkripsiFileService     $fileService,
    ) {}

    private function admin(): Admin
    {
        return Admin::where('user_id', Auth::id())->firstOrFail();
    }

    // ── Dashboard ─────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'proposal');

        $proposals = SkripsiSubmission::whereIn('status', [
                SkripsiStatus::PROPOSAL_SUBMITTED,
                SkripsiStatus::PROPOSAL_APPROVED,
                SkripsiStatus::PROPOSAL_REJECTED,
            ])
            ->with('mahasiswa.user', 'requestedSupervisor')
            ->latest()
            ->get();

        $activeBimbingan = SkripsiSubmission::whereIn('status', [
                SkripsiStatus::BIMBINGAN_ACTIVE,
                SkripsiStatus::ELIGIBLE_SIDANG,
            ])
            ->with('mahasiswa.user', 'approvedSupervisor')
            ->latest()
            ->get();

        $pendingSidang = SkripsiSidangRegistration::where('status', 'submitted')
            ->with('submission.mahasiswa.user')
            ->latest()
            ->get();

        $scheduled = SkripsiSubmission::where('status', SkripsiStatus::SIDANG_SCHEDULED)
            ->with('mahasiswa.user', 'sidangSchedule.pembimbing', 'sidangSchedule.penguji1')
            ->latest()
            ->get();

        $revisions = SkripsiSubmission::where('status', SkripsiStatus::REVISION_UPLOADED)
            ->with('mahasiswa.user', 'latestRevision')
            ->latest()
            ->get();

        $completed = SkripsiSubmission::where('status', SkripsiStatus::SKRIPSI_COMPLETED)
            ->with('mahasiswa.user')
            ->latest()
            ->get();

        return view('admin.skripsi.index', compact(
            'tab', 'proposals', 'activeBimbingan', 'pendingSidang', 'scheduled', 'revisions', 'completed'
        ));
    }

    // ── Show Detail ───────────────────────────────────────────────────────

    public function show(SkripsiSubmission $skripsi)
    {
        $skripsi->load([
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

        return view('admin.skripsi.show', compact('skripsi'));
    }

    // ── Proposal Actions ──────────────────────────────────────────────────

    public function approveProposal(Request $request, SkripsiSubmission $skripsi)
    {
        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->workflow->approveProposal($skripsi, $this->admin(), $request->note);

        return back()->with('success', 'Proposal disetujui. Bimbingan aktif.');
    }

    public function rejectProposal(Request $request, SkripsiSubmission $skripsi)
    {
        $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $this->workflow->rejectProposal($skripsi, $this->admin(), $request->reason);

        return back()->with('success', 'Proposal ditolak. Mahasiswa dapat mengajukan ulang.');
    }

    // ── Sidang Registration Actions ───────────────────────────────────────

    public function verifySidangRegistration(Request $request, SkripsiSidangRegistration $reg)
    {
        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $this->workflow->verifySidangRegistration($reg, $this->admin(), $request->note);

        return back()->with('success', 'Pendaftaran sidang terverifikasi. Silakan tentukan jadwal.');
    }

    public function rejectSidangRegistration(Request $request, SkripsiSidangRegistration $reg)
    {
        $request->validate(['reason' => ['required', 'string', 'max:1000']]);
        $this->workflow->rejectSidangRegistration($reg, $this->admin(), $request->reason);

        return back()->with('success', 'Pendaftaran sidang ditolak.');
    }

    // ── Schedule Sidang ───────────────────────────────────────────────────

    public function scheduleForm(SkripsiSubmission $skripsi)
    {
        $skripsi->load(['sidangRegistration.files', 'mahasiswa.user']);
        $dosens   = Dosen::select('dosens.*')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->get();
        $ruangans = Ruangan::where('status', 'aktif')->orWhereNull('status')->orderBy('nama_ruangan')->get();

        return view('admin.skripsi.schedule', compact('skripsi', 'dosens', 'ruangans'));
    }

    public function storeSidangSchedule(ScheduleSidangRequest $request, SkripsiSubmission $skripsi)
    {
        $this->sidangService->schedule($skripsi, $request->validated(), $this->admin());

        return redirect()->route('admin.skripsi.show', $skripsi)
            ->with('success', 'Jadwal sidang berhasil ditetapkan.');
    }

    // ── Mark Sidang Completed ─────────────────────────────────────────────

    public function completeSidang(SkripsiSubmission $skripsi)
    {
        $this->workflow->completeSidang($skripsi);

        return back()->with('success', 'Sidang ditandai selesai. Mahasiswa masuk masa revisi.');
    }

    // ── File Download ─────────────────────────────────────────────────────

    public function downloadFile(string $path)
    {
        $decoded = base64_decode($path);
        abort_unless($decoded && str_starts_with($decoded, 'skripsi/'), 403);

        return $this->fileService->downloadResponse($decoded, basename($decoded));
    }

    public function previewFile(string $path)
    {
        $decoded = base64_decode($path);
        abort_unless($decoded && str_starts_with($decoded, 'skripsi/'), 403);

        return $this->fileService->previewResponse($decoded);
    }
}
