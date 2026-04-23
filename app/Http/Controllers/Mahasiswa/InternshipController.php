<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipLogbook;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Services\InternshipWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InternshipController extends Controller
{
    public function __construct(private InternshipWorkflowService $workflow) {}

    /**
     * Index — list mahasiswa's internships.
     */
    public function index()
    {
        $mahasiswa   = Auth::user()->mahasiswa;
        $internships = $mahasiswa->internships()->with('semester', 'supervisorDosen.user')->latest()->get();
        $semesters   = Semester::orderByDesc('id')->get();

        return view('page.mahasiswa.magang.index', compact('internships', 'semesters'));
    }

    /**
     * Create form.
     */
    public function create()
    {
        $mahasiswa      = Auth::user()->mahasiswa;
        $activeSemester = Semester::where('is_active', true)->first()
            ?? Semester::orderByDesc('id')->first();
        $mataKuliahs    = MataKuliah::orderBy('nama_mk')->get();

        // Mahasiswa harus minimal semester 5 untuk mendaftar magang
        if ((int) ($mahasiswa->semester ?? 0) < 5) {
            return redirect()->route('mahasiswa.magang.index')
                ->with('error', 'Pendaftaran magang hanya dapat dilakukan mulai Semester 5. Semester Anda saat ini adalah Semester ' . ($mahasiswa->semester ?? 1) . '.');
        }

        $blockedStatuses = [
            Internship::STATUS_APPROVED,
            Internship::STATUS_SENT_TO_STUDENT,
            Internship::STATUS_SUPERVISOR_ASSIGNED,
            Internship::STATUS_ACCEPTANCE_LETTER_READY,
            Internship::STATUS_ONGOING,
        ];
        $hasApprovedInternship = Internship::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', $blockedStatuses)
            ->exists();
        if ($hasApprovedInternship) {
            return redirect()->route('mahasiswa.magang.index')
                ->with('error', 'Anda sudah memiliki magang yang telah disetujui. Pengajuan baru tidak dapat dilakukan.');
        }

        return view('page.mahasiswa.magang.create', compact('activeSemester', 'mataKuliahs', 'mahasiswa'));
    }

    /**
     * Store a new internship draft.
     */
    public function store(Request $request)
    {
        $request->validate([
            'instansi'               => 'required|string|max:255',
            'alamat_instansi'        => 'required|string|max:500',
            'posisi'                 => 'nullable|string|max:255',
            'periode_mulai'          => 'required|date',
            'periode_selesai'        => 'required|date|after:periode_mulai',
            'deskripsi'              => 'nullable|string|max:2000',
            'pembimbing_lapangan_nama'  => 'nullable|string|max:255',
            'pembimbing_lapangan_telp'  => ['nullable', 'string', 'regex:/^[0-9]{12,13}$/'],
            'dokumen_pendukung'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ], [
            'pembimbing_lapangan_telp.regex' => 'No. Telp Pembimbing harus terdiri dari 12 hingga 13 angka.',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $activeSemester = Semester::where('is_active', true)->first()
            ?? Semester::orderByDesc('id')->first();

        // Semester validation
        if ((int) ($mahasiswa->semester ?? 0) < 5) {
            return redirect()->route('mahasiswa.magang.index')
                ->with('error', 'Pendaftaran magang hanya dapat dilakukan mulai Semester 5. Semester Anda saat ini adalah Semester ' . ($mahasiswa->semester ?? 1) . '.');
        }

        $blockedStatuses = [
            Internship::STATUS_APPROVED,
            Internship::STATUS_SENT_TO_STUDENT,
            Internship::STATUS_SUPERVISOR_ASSIGNED,
            Internship::STATUS_ACCEPTANCE_LETTER_READY,
            Internship::STATUS_ONGOING,
        ];
        $hasApprovedInternship = Internship::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', $blockedStatuses)
            ->exists();
        if ($hasApprovedInternship) {
            return redirect()->route('mahasiswa.magang.index')
                ->with('error', 'Anda sudah memiliki magang yang telah disetujui. Pengajuan baru tidak dapat dilakukan.');
        }

        if (!$activeSemester) {
            return redirect()->back()
                ->with('error', 'Tidak ada semester aktif. Silakan hubungi admin untuk mengaktifkan semester terlebih dahulu.')
                ->withInput();
        }

        $data = $request->only([
            'instansi', 'alamat_instansi', 'posisi',
            'periode_mulai', 'periode_selesai', 'deskripsi',
            'pembimbing_lapangan_nama', 'pembimbing_lapangan_telp',
        ]);
        $data['semester_mahasiswa'] = $mahasiswa->semester;


        if ($request->hasFile('dokumen_pendukung')) {
            $targetFolder = 'documents/internship/dokumen/' . $mahasiswa->storage_folder;
            $fileName = \Illuminate\Support\Str::uuid() . '.' . $request->file('dokumen_pendukung')->getClientOriginalExtension();
            $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $fileName);
            $data['dokumen_pendukung_path'] = $request->file('dokumen_pendukung')->storeAs($targetFolder, $fileName, $resolvedDisk);
        }

        $internship = $this->workflow->createDraft($mahasiswa->id, $activeSemester->id, $data);

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Data magang berhasil disimpan sebagai draft.');
    }

    /**
     * Show detail for a specific internship.
     */
    public function show(Internship $internship)
    {
        $this->authorizeView($internship);
        $internship->load(['semester', 'supervisorDosen.user', 'courseMappings.mataKuliah', 'logbooks', 'revisions', 'krsEntries.nilai', 'krsEntries.mataKuliah']);

        return view('page.mahasiswa.magang.show', compact('internship'));
    }

    /**
     * Edit form (only if editable).
     */
    public function edit(Internship $internship)
    {
        $this->authorizeView($internship);
        if (!$internship->isEditable()) {
            return redirect()->route('mahasiswa.magang.show', $internship)
                ->with('error', 'Magang ini tidak bisa diedit.');
        }

        $activeSemester = Semester::where('is_active', true)->first();
        return view('page.mahasiswa.magang.edit', compact('internship', 'activeSemester'));
    }

    /**
     * Update internship data.
     */
    public function update(Request $request, Internship $internship)
    {
        $this->authorizeView($internship);

        $request->validate([
            'instansi'               => 'required|string|max:255',
            'alamat_instansi'        => 'required|string|max:500',
            'posisi'                 => 'nullable|string|max:255',
            'periode_mulai'          => 'required|date',
            'periode_selesai'        => 'required|date|after:periode_mulai',
            'deskripsi'              => 'nullable|string|max:2000',
            'pembimbing_lapangan_nama'  => 'nullable|string|max:255',
            'pembimbing_lapangan_telp'  => ['nullable', 'string', 'regex:/^[0-9]{12,13}$/'],
        ], [
            'pembimbing_lapangan_telp.regex' => 'No. Telp Pembimbing harus terdiri dari 12 hingga 13 angka.',
        ]);

        $this->workflow->updateData($internship, $request->only([
            'instansi', 'alamat_instansi', 'posisi',
            'periode_mulai', 'periode_selesai', 'deskripsi',
            'pembimbing_lapangan_nama', 'pembimbing_lapangan_telp',
        ]));

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Data magang berhasil diperbarui.');
    }

    /**
     * Submit the draft → progresses to WAITING_REQUEST_LETTER.
     */
    public function submit(Internship $internship)
    {
        $this->authorizeView($internship);
        $this->workflow->submit($internship);

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Pengajuan magang berhasil disubmit.');
    }

    /**
     * Generate the request letter (Surat Permohonan Magang).
     */
    public function generateLetter(Internship $internship)
    {
        $this->authorizeView($internship);

        try {
            $path = $this->workflow->generateRequestLetter($internship);
            return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($path))->download($path, 'Surat_Pengantar_Magang.docx');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal generate surat: ' . $e->getMessage());
        }
    }

    /**
     * Upload sent signed request letter.
     */
    public function uploadSigned(Request $request, Internship $internship)
    {
        $this->authorizeView($internship);

        $request->validate([
            'signed_letter' => 'required|file|mimes:pdf,docx,jpg,png|max:5120',
        ]);

        $this->workflow->uploadSignedRequestLetter($internship, $request->file('signed_letter'));

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Surat yang sudah ditandatangani berhasil diunggah.');
    }

    /**
     * Mahasiswa uploads acceptance letter received from the company (instansi).
     * This only saves the file. Admin must confirm separately before magang can start.
     */
    public function uploadAcceptance(Request $request, Internship $internship)
    {
        $this->authorizeView($internship);

        $request->validate([
            'acceptance_letter' => 'required|file|mimes:pdf,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            $this->workflow->saveMahasiswaAcceptanceLetter($internship, $request->file('acceptance_letter'));
            return redirect()->route('mahasiswa.magang.show', $internship)
                ->with('success', 'Surat penerimaan berhasil diunggah. Menunggu konfirmasi dari admin sebelum magang dapat dimulai.');
        } catch (\Throwable $e) {
            return redirect()->route('mahasiswa.magang.show', $internship)
                ->with('error', 'Gagal mengunggah surat penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Submit for admin review.
     */
    public function submitForReview(Request $request, Internship $internship)
    {
        $this->authorizeView($internship);
        $this->workflow->submitForReview($internship, $request->input('note'));

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Pengajuan berhasil dikirim untuk review admin.');
    }

    /**
     * Add logbook entry.
     */
    public function storeLogbook(Request $request, Internship $internship)
    {
        $this->authorizeView($internship);

        $request->validate([
            'tanggal'  => 'required|date',
            'kegiatan' => 'required|string|max:2000',
        ]);

        InternshipLogbook::create([
            'internship_id'  => $internship->id,
            'tanggal'        => $request->tanggal,
            'kegiatan'       => $request->kegiatan,
            'created_by_role' => 'mahasiswa',
        ]);

        return redirect()->route('mahasiswa.magang.show', $internship)
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * Download admin-signed official PDF (Surat Pengantar Resmi TTD).
     */
    public function downloadOfficial(Internship $internship)
    {
        $this->authorizeView($internship);

        $path = $internship->admin_signed_pdf_path ?? $internship->admin_final_pdf_path;

        return \Illuminate\Support\Facades\Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($path))->download(
            $path,
            "Surat_Pengantar_Resmi_{$nim}.{$ext}"
        );
    }

    /**
     * Download generated acceptance letter.
     */
    public function downloadAcceptanceLetter(Internship $internship)
    {
        $this->authorizeView($internship);

        if (!$internship->acceptance_letter_path) {
            return redirect()->back()->with('error', 'Surat penerimaan belum tersedia.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($internship->acceptance_letter_path))->download(
            $internship->acceptance_letter_path,
            'Surat_Penerimaan_Magang.docx'
        );
    }

    /**
     * Delete an internship.
     */
    public function destroy(Internship $internship)
    {
        $this->authorizeView($internship);

        try {
            $this->workflow->delete($internship);
            return redirect()->route('mahasiswa.magang.index')->with('success', 'Pengajuan magang berhasil dihapus.');
        } catch (\LogicException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Simple ownership check.
     */
    private function authorizeView(Internship $internship): void
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa || $internship->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Unauthorized');
        }
    }
}
