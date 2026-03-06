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
        $activeSemester = Semester::where('is_active', true)->first();
        $mataKuliahs    = MataKuliah::orderBy('nama_mk')->get();

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
            'pembimbing_lapangan_telp'  => 'nullable|string|max:50',
            'dokumen_pendukung'      => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $activeSemester = Semester::where('is_active', true)->firstOrFail();

        $data = $request->only([
            'instansi', 'alamat_instansi', 'posisi',
            'periode_mulai', 'periode_selesai', 'deskripsi',
            'pembimbing_lapangan_nama', 'pembimbing_lapangan_telp',
        ]);
        $data['semester_mahasiswa'] = $mahasiswa->semester;


        if ($request->hasFile('dokumen_pendukung')) {
            $data['dokumen_pendukung_path'] = $request->file('dokumen_pendukung')
                ->store('internship/dokumen', 'public');
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
        $internship->load(['semester', 'supervisorDosen.user', 'courseMappings.mataKuliah', 'logbooks', 'revisions']);

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
            'pembimbing_lapangan_telp'  => 'nullable|string|max:50',
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
            return Storage::disk('public')->download($path, 'Surat_Permohonan_Magang.docx');
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
     * Download generated acceptance letter.
     */
    public function downloadAcceptanceLetter(Internship $internship)
    {
        $this->authorizeView($internship);

        if (!$internship->acceptance_letter_path) {
            return redirect()->back()->with('error', 'Surat penerimaan belum tersedia.');
        }

        return Storage::disk('public')->download(
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
