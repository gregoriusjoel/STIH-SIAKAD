<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Services\PengajuanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function __construct(private PengajuanService $service)
    {
    }

    // ── Daftar pengajuan bebas keuangan ───────────────────────────

    public function index(Request $request)
    {
        $query = Pengajuan::where('jenis', 'bebas_keuangan')
            ->with(['mahasiswa.user', 'approver'])
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $pengajuans = $query->paginate(15);

        $stats = [
            'total'     => Pengajuan::where('jenis', 'bebas_keuangan')->count(),
            'submitted' => Pengajuan::where('jenis', 'bebas_keuangan')->where('status', Pengajuan::STATUS_SUBMITTED)->count(),
            'approved'  => Pengajuan::where('jenis', 'bebas_keuangan')->where('status', Pengajuan::STATUS_APPROVED)->count(),
            'rejected'  => Pengajuan::where('jenis', 'bebas_keuangan')->where('status', Pengajuan::STATUS_REJECTED)->count(),
        ];

        return view('finance.pengajuan.index', compact('pengajuans', 'stats'));
    }

    // ── Detail pengajuan ──────────────────────────────────────────

    public function show(Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        $pengajuan->load(['mahasiswa.user', 'approver', 'revisions']);
        return view('finance.pengajuan.show', compact('pengajuan'));
    }

    // ── Approve ───────────────────────────────────────────────────

    public function approve(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($pengajuan->status !== Pengajuan::STATUS_SUBMITTED) {
            return redirect()->back()->with('error', 'Hanya pengajuan berstatus "Menunggu Review" yang dapat disetujui.');
        }

        $this->service->approve($pengajuan, Auth::id(), $request->admin_note);

        return redirect()->back()->with('success', 'Pengajuan bebas keuangan berhasil disetujui dan surat diterbitkan secara otomatis!');
    }

    // ── Reject ────────────────────────────────────────────────────

    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        if ($pengajuan->status !== Pengajuan::STATUS_SUBMITTED) {
            return redirect()->back()->with('error', 'Hanya pengajuan berstatus "Menunggu Review" yang dapat ditolak.');
        }

        $this->service->reject($pengajuan, Auth::id(), $request->rejected_reason);

        return redirect()->back()->with('success', 'Pengajuan telah ditolak. Mahasiswa akan diberitahu untuk melakukan perbaikan.');
    }

    // ── Download signed document (dari mahasiswa) ─────────────────

    public function downloadSigned(Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        if (!$pengajuan->signed_doc_path) {
            return redirect()->back()->with('error', 'Dokumen bertanda tangan belum tersedia.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->signed_doc_path))->download(
            $pengajuan->signed_doc_path,
            'Signed_' . $pengajuan->mahasiswa->nim . '_' . $pengajuan->jenis_label . '.' . pathinfo($pengajuan->signed_doc_path, PATHINFO_EXTENSION)
        );
    }

    // ── Download generated document ───────────────────────────────

    public function downloadGenerated(Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        if (!$pengajuan->generated_doc_path) {
            return redirect()->back()->with('error', 'Dokumen generate belum tersedia.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->generated_doc_path))->download($pengajuan->generated_doc_path);
    }

    // ── Download final approved letter ────────────────────────────

    public function download(Pengajuan $pengajuan)
    {
        $this->authorizeFinancePengajuan($pengajuan);
        if (!$pengajuan->file_surat) {
            return redirect()->back()->with('error', 'Surat final belum tersedia.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($pengajuan->file_surat))->download($pengajuan->file_surat);
    }

    // ── Helper: Pastikan hanya bebas_keuangan ─────────────────────

    private function authorizeFinancePengajuan(Pengajuan $pengajuan): void
    {
        if ($pengajuan->jenis !== 'bebas_keuangan') {
            abort(403, 'Unauthorized access');
        }
    }
}
