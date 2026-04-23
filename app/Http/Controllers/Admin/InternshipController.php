<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Internship;
use App\Models\InternshipCourseMapping;
use App\Models\MataKuliah;
use App\Services\InternshipGradingService;
use App\Services\InternshipWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InternshipController extends Controller
{
    public function __construct(
        private InternshipWorkflowService $workflow,
        private InternshipGradingService $gradingService,
    ) {}

    /**
     * List all internships with filters.
     */
    public function index(Request $request)
    {
        $query = Internship::with(['mahasiswa.user', 'semester', 'supervisorDosen.user'])->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('mahasiswa', fn($q) =>
                $q->where('nim', 'like', "%{$s}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$s}%"))
            );
        }

        $internships = $query->paginate(15);

        $stats = [
            'total'     => Internship::count(),
            'submitted' => Internship::where('status', Internship::STATUS_UNDER_REVIEW)->count(),
            'ongoing'   => Internship::where('status', Internship::STATUS_ONGOING)->count(),
            'completed' => Internship::where('status', Internship::STATUS_COMPLETED)->count(),
        ];

        return view('admin.magang.index', compact('internships', 'stats'));
    }

    /**
     * Show detail for one internship.
     */
    public function show(Internship $internship)
    {
        $internship->load([
            'mahasiswa.user', 'semester', 'supervisorDosen.user',
            'courseMappings.mataKuliah', 'logbooks', 'revisions', 'approver',
            'sentBy', 'dateChangedBy',
        ]);

        $dosens = Dosen::with('user')->get();

        $studentSemester = $internship->semester_mahasiswa ?? $internship->mahasiswa->semester;
        $mataKuliahsQuery = MataKuliah::query();
        if ($studentSemester) {
            $mataKuliahsQuery->where('semester', $studentSemester);
        }
        $mataKuliahs = $mataKuliahsQuery->orderBy('nama_mk')->get();

        $gradeSummary = $this->gradingService->getGradeSummary($internship);

        // Grade map for realtime calculator (JS)
        $gradeMap = InternshipGradingService::GRADE_MAP;

        return view('admin.magang.show', compact('internship', 'dosens', 'mataKuliahs', 'gradeSummary', 'gradeMap'));
    }

    /**
     * Verify the current admin's password (AJAX).
     */
    public function verifyPassword(Request $request)
    {
        $request->validate(['password' => 'required|string']);
        $ok = Hash::check($request->password, Auth::user()->password);
        return response()->json(['ok' => $ok]);
    }

    /**
     * Approve an internship that is under review.
     */
    public function approve(Request $request, Internship $internship)
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);
        $this->workflow->approve($internship, Auth::id(), $request->admin_note);
        return redirect()->back()->with('success', 'Pengajuan magang berhasil disetujui. Silakan generate PDF resmi.');
    }

    /**
     * Reject with reason.
     */
    public function reject(Request $request, Internship $internship)
    {
        $request->validate(['rejected_reason' => 'required|string|max:1000']);
        $this->workflow->reject($internship, Auth::id(), $request->rejected_reason);
        return redirect()->back()->with('success', 'Pengajuan magang ditolak. Mahasiswa dapat merevisi.');
    }

    // ─── NEW: Generate Official PDF ────────────────────────────────────────────

    /**
     * Admin generates the official PDF (pixel-perfect from DOCX template via LibreOffice).
     */
    public function generateOfficialPdf(Request $request, Internship $internship)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
        ]);

        try {
            $path = $this->workflow->generateOfficialPdf($internship, $request->nomor_surat);
            $ext  = pathinfo($path, PATHINFO_EXTENSION);
            if ($ext === 'pdf') {
                $msg = 'Surat resmi berhasil digenerate sebagai PDF dan sedang diunduh otomatis...';
            } else {
                $msg = 'Surat resmi berhasil digenerate sebagai DOCX dan disimpan. Gunakan tombol "Download Surat Resmi" untuk mengunduh. Jika diinginkan PDF otomatis, pasang LibreOffice di server.';
            }

            return redirect()->back()->with('success', $msg)
                             ->with('generated_path', $path)
                             ->with('auto_download_official', true);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download admin's generated official PDF/DOCX.
     */
    public function downloadOfficialPdf(Internship $internship)
    {
        if (!$internship->admin_final_pdf_path) {
            return redirect()->back()->with('error', 'PDF resmi belum digenerate.');
        }

        $ext = pathinfo($internship->admin_final_pdf_path, PATHINFO_EXTENSION);
        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($internship->admin_final_pdf_path))->download(
            $internship->admin_final_pdf_path,
            'Surat_Permohonan_Resmi_' . ($internship->mahasiswa?->nim ?? $internship->id) . '.' . $ext
        );
    }

    /**
     * Admin uploads the signed/stamped final PDF.
     */
    public function uploadSignedPdf(Request $request, Internship $internship)
    {
        $request->validate([
            'signed_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $this->workflow->uploadAdminSignedPdf($internship, $request->file('signed_pdf'));
            // Auto send to student
            $this->workflow->sendToStudent($internship, Auth::id());
            return redirect()->back()->with('success', 'PDF bertandatangan berhasil diupload dan otomatis dikirim ke mahasiswa.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    /**
     * Download admin's signed PDF.
     */
    public function downloadSignedPdf(Internship $internship)
    {
        $path = $internship->admin_signed_pdf_path ?? $internship->admin_final_pdf_path;
        if (!$path) {
            return redirect()->back()->with('error', 'PDF belum tersedia.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($path))->download(
            $path,
            'Surat_Permohonan_Resmi_Signed_' . ($internship->mahasiswa?->nim ?? $internship->id) . '.pdf'
        );
    }

    /**
     * Admin sends the official letter to student → advance status to SENT_TO_STUDENT.
     */
    public function sendToStudent(Internship $internship)
    {
        try {
            $this->workflow->sendToStudent($internship, Auth::id());
            return redirect()->back()->with('success', 'Surat resmi berhasil dikirim ke mahasiswa.');
        } catch (\LogicException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ─── Supervisor & Acceptance Letter ────────────────────────────────────────

    /**
     * Assign a supervisor dosen.
     */
    public function assignSupervisor(Request $request, Internship $internship)
    {
        $request->validate(['supervisor_dosen_id' => 'required|exists:dosens,id']);
        $this->workflow->assignSupervisor($internship, $request->supervisor_dosen_id);
        return redirect()->back()->with('success', 'Dosen pembimbing berhasil ditetapkan.');
    }

    /**
     * Upload acceptance letter received from the company (instansi),
     * then advance status to ACCEPTANCE_LETTER_READY.
     */
    public function uploadAcceptanceLetter(Request $request, Internship $internship)
    {
        $request->validate([
            'acceptance_letter' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        try {
            $this->workflow->receiveAcceptanceLetter($internship, $request->file('acceptance_letter'));
            return redirect()->back()->with('success', 'Surat penerimaan dari instansi telah dikonfirmasi.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Start internship (ONGOING).
     */
    public function startInternship(Internship $internship)
    {
        $this->workflow->startInternship($internship);
        return redirect()->back()->with('success', 'Magang dimulai. KRS konversi telah diinjeksi.');
    }

    /**
     * Mark completed.
     */
    public function markCompleted(Internship $internship)
    {
        $this->workflow->markCompleted($internship);
        return redirect()->back()->with('success', 'Magang ditandai selesai.');
    }

    // ─── Date Management ────────────────────────────────────────────────────────

    /**
     * Update internship period dates with audit trail.
     */
    public function updateDates(Request $request, Internship $internship)
    {
        $request->validate([
            'periode_mulai'      => 'required|date',
            'periode_selesai'    => 'required|date|after:periode_mulai',
            'date_change_reason' => 'required|string|max:500',
        ]);

        try {
            $this->workflow->updatePeriodDates(
                $internship,
                $request->periode_mulai,
                $request->periode_selesai,
                Auth::id(),
                $request->date_change_reason,
            );
            return redirect()->back()->with('success', 'Tanggal magang berhasil diperbarui.');
        } catch (\LogicException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ─── Grading ────────────────────────────────────────────────────────────────

    /**
     * Save course mappings (MK konversi + SKS).
     */
    public function saveCourseMappings(Request $request, Internship $internship)
    {
        $request->validate([
            'mappings'                 => 'required|array|min:1',
            'mappings.*.mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'mappings.*.sks'           => 'required|integer|min:1|max:20',
            'max_sks'                  => 'nullable|integer|min:1|max:40',
        ]);

        $maxSks   = (int) ($request->max_sks ?? 16);
        $totalSks = collect($request->mappings)->sum('sks');

        if ($totalSks > $maxSks) {
            return redirect()->back()->with('error', "Total SKS konversi ({$totalSks}) melebihi batas {$maxSks} SKS.");
        }

        InternshipCourseMapping::where('internship_id', $internship->id)->delete();
        foreach ($request->mappings as $m) {
            InternshipCourseMapping::create([
                'internship_id'  => $internship->id,
                'mata_kuliah_id' => $m['mata_kuliah_id'],
                'sks'            => $m['sks'],
            ]);
        }

        return redirect()->back()->with('success', 'Mapping mata kuliah konversi berhasil disimpan.');
    }

    /**
     * Input grades for conversion courses.
     */
    public function inputGrades(Request $request, Internship $internship)
    {
        $request->validate([
            'grades'               => 'required|array',
            'grades.*.nilai_akhir' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $this->gradingService->inputGrades($internship, $request->grades);
            $this->workflow->markGraded($internship);
            return redirect()->back()->with('success', 'Nilai konversi berhasil disimpan dan diterbitkan ke mahasiswa.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal input nilai: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: preview grade/IPS before saving.
     * Input: JSON { items: [{ nilai_akhir: 85, sks: 3 }, ...] }
     * Used by realtime kalkulator di UI.
     */
    public function previewGrade(Request $request, Internship $internship)
    {
        $request->validate([
            'items'               => 'required|array',
            'items.*.nilai_akhir' => 'required|numeric|min:0|max:100',
            'items.*.sks'         => 'required|integer|min:0',
        ]);

        $preview = $this->gradingService->previewIps($request->items);

        return response()->json($preview);
    }

    /**
     * Close internship.
     */
    public function close(Internship $internship)
    {
        $this->workflow->close($internship);
        return redirect()->back()->with('success', 'Magang ditutup.');
    }
}
