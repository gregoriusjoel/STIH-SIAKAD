<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengajuanRequest;
use App\Http\Requests\UploadSignedDocRequest;
use App\Models\Pengajuan;
use App\Services\PengajuanService;
use App\Support\LetterTemplateConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function __construct(private PengajuanService $service) {}

    // ── Halaman utama ─────────────────────────────────────────────

    public function index()
    {
        $mahasiswa     = Auth::user()->mahasiswa;
        $allPengajuans = $mahasiswa->pengajuans()->latest()->get();
        $pengajuans    = $mahasiswa->pengajuans()->with('revisions')->latest()->paginate(10);
        $jenisOptions  = LetterTemplateConfig::options();
        $jenisConfig   = LetterTemplateConfig::all();

        // Collect mahasiswa's enrolled courses for the mata_kuliah_ditinggal dropdown (dispensasi)
        // KRS links to KelasMataKuliah which links to MataKuliah (column: nama_mk)
        $currentCourses = \App\Models\Krs::with('kelasMataKuliah.mataKuliah')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->get()
            ->map(fn($k) => [
                'id'      => $k->kelasMataKuliah?->mataKuliah?->id,
                'kode'    => $k->kelasMataKuliah?->mataKuliah?->kode_mk ?? '',
                'display' => trim((string)($k->kelasMataKuliah?->mataKuliah?->nama_mk ?? '')),
            ])
            ->filter(fn($c) => !empty($c['display']))
            ->unique('id')
            ->values();

        return view('page.mahasiswa.pengajuan.index', compact(
            'pengajuans', 'allPengajuans', 'jenisOptions', 'jenisConfig', 'currentCourses'
        ));
    }

    // ── AJAX: kembalikan config fields untuk jenis tertentu ───────

    public function jenisConfig(string $jenis)
    {
        $config = LetterTemplateConfig::get($jenis);
        if (!$config) {
            return response()->json(['error' => 'Jenis tidak valid'], 422);
        }
        return response()->json([
            'fields' => $config['fields'],
            'label'  => $config['label'],
        ]);
    }

    // ── Step 1: Buat draft ────────────────────────────────────────

    public function store(StorePengajuanRequest $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $pengajuan = $this->service->createDraft(
            mahasiswaId: $mahasiswa->id,
            jenis:       $request->jenis,
            keterangan:  $request->keterangan,
            payload:     $request->input('payload_template', []),
        );

        return response()->json([
            'message'      => 'Draft berhasil dibuat.',
            'pengajuan_id' => $pengajuan->id,
            'status'       => $pengajuan->status,
        ]);
    }

    // ── Step 2: Trigger generate dokumen (async via queue) ────────

    public function generate(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->canGenerateDoc()) {
            return response()->json([
                'error' => 'Dokumen sudah pernah digenerate atau status tidak valid.',
            ], 422);
        }

        $this->service->dispatchGenerate($pengajuan);

        return response()->json([
            'message' => 'Dokumen sedang diproses. Mohon tunggu beberapa saat.',
            'status'  => $pengajuan->status,
        ]);
    }

    // ── Polling status (untuk frontend menunggu generate selesai) ─

    public function statusCheck(Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);
        $pengajuan->refresh();

        return response()->json([
            'status'              => $pengajuan->status,
            'generated_doc_ready' => !empty($pengajuan->generated_doc_path),
            'signed_doc_uploaded' => !empty($pengajuan->signed_doc_path),
            'can_submit'          => $pengajuan->canSubmit(),
        ]);
    }

    // ── Step 3: Download dokumen yang sudah digenerate ───────────

    public function downloadGenerated(Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->canDownloadGenerated()) {
            return redirect()->back()->with('error', 'Dokumen belum tersedia untuk diunduh.');
        }

        return Storage::disk('s3')->download(
            $pengajuan->generated_doc_path,
            'Surat_' . $pengajuan->jenis_label . '_' . $pengajuan->mahasiswa->nim . '.docx'
        );
    }

    // ── Step 4: Upload dokumen bertanda tangan ────────────────────

    public function uploadSigned(UploadSignedDocRequest $request, Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->canUploadSigned()) {
            return response()->json(['error' => 'Upload tidak diizinkan pada status ini.'], 422);
        }

        $this->service->uploadSignedDoc($pengajuan, $request->file('signed_doc'));

        return response()->json([
            'message'    => 'Dokumen berhasil di-upload. Silakan kirim pengajuan.',
            'can_submit' => true,
        ]);
    }

    // ── Step 5: Submit ke admin ────────────────────────────────────

    public function submit(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->canSubmit()) {
            return response()->json(['error' => 'Upload dokumen bertanda tangan terlebih dahulu.'], 422);
        }

        $this->service->submit($pengajuan, $request->input('revision_note'));

        return response()->json(['message' => 'Pengajuan berhasil dikirim ke admin.']);
    }

    // ── Hapus pengajuan (mahasiswa) ───────────────────────────────────
    public function destroy(Request $request, Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        // Only allow deleting drafts, generated (not submitted), or rejected items
        if (!in_array($pengajuan->status, [Pengajuan::STATUS_DRAFT, Pengajuan::STATUS_GENERATED, Pengajuan::STATUS_REJECTED])) {
            return response()->json(['error' => 'Pengajuan tidak dapat dihapus pada status ini.'], 422);
        }

        // delegate deletion to service (delete files + model)
        $this->service->deletePengajuan($pengajuan);

        return response()->json(['message' => 'Pengajuan berhasil dihapus.']);
    }

    // ── Download dokumen final (setelah approved) ─────────────────

    public function download(Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->file_surat || $pengajuan->status !== Pengajuan::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'Surat belum tersedia atau pengajuan belum disetujui.');
        }

        return Storage::disk('s3')->download(
            $pengajuan->file_surat,
            'Surat_Approved_' . $pengajuan->mahasiswa->nim . '.pdf'
        );
    }

    // ── Preview surat final ───────────────────────────────────────

    public function preview(Pengajuan $pengajuan)
    {
        $this->authorizePengajuan($pengajuan);

        if (!$pengajuan->file_surat || $pengajuan->status !== Pengajuan::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'Surat belum tersedia atau pengajuan belum disetujui.');
        }

        return Storage::disk('s3')->response(
            $pengajuan->file_surat,
            'preview.pdf',
            ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="preview.pdf"']
        );
    }

    // ── Helper: pastikan pengajuan milik mahasiswa login ─────────

    private function authorizePengajuan(Pengajuan $pengajuan): void
    {
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Unauthorized access');
        }
    }
}
