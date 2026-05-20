<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\PrestasiSurat;
use App\Models\PrestasiSuratSetting;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Services\PrestasiService;
use App\Services\PrestasiLetterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function __construct(
        private PrestasiService $prestasiService,
        private PrestasiLetterService $letterService,
    ) {}

    /**
     * Dashboard + list all prestasi.
     */
    public function index(Request $request)
    {
        $query = Prestasi::with(['pengaju.user', 'dosenPendamping.user', 'surats'])
            ->orderByDesc('updated_at');

        // Filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        if ($request->filled('tingkat')) {
            $query->byTingkat($request->tingkat);
        }
        if ($request->filled('role')) {
            $type = $request->role === 'mahasiswa' ? Mahasiswa::class : Dosen::class;
            $query->where('pengaju_type', $type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_kegiatan', 'like', "%{$s}%")
                  ->orWhere('penyelenggara', 'like', "%{$s}%")
                  ->orWhereHas('pengaju', function ($q2) use ($s) {
                      $q2->whereHas('user', fn($q3) => $q3->where('name', 'like', "%{$s}%"));
                  });
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('tanggal_mulai', '<=', $request->to_date);
        }

        $prestasis = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'      => Prestasi::count(),
            'pending'    => Prestasi::pending()->count(),
            'mahasiswa'  => Prestasi::where('pengaju_type', Mahasiswa::class)->count(),
            'dosen'      => Prestasi::where('pengaju_type', Dosen::class)->count(),
            'internal'   => Prestasi::byTingkat('internal')->count(),
            'regional'   => Prestasi::byTingkat('regional')->count(),
            'nasional'   => Prestasi::byTingkat('nasional')->count(),
            'internasional' => Prestasi::byTingkat('internasional')->count(),
        ];

        // Surat settings for the settings modal
        $suratSettings = $this->letterService->getSettings();

        return view('admin.prestasi.index', compact('prestasis', 'stats', 'suratSettings'));
    }

    /**
     * Show detail of a prestasi with timeline.
     */
    public function show(Prestasi $prestasi)
    {
        $prestasi->load([
            'pengaju.user',
            'dosenPendamping.user',
            'dokumens.uploader',
            'logs.user',
            'surats.generator',
            'approver',
        ]);

        $dosens = Dosen::with('user')->whereHas('user')->get();

        return view('admin.prestasi.show', compact('prestasi', 'dosens'));
    }

    /**
     * Approve a prestasi.
     */
    public function approve(Request $request, Prestasi $prestasi)
    {
        $this->prestasiService->approve($prestasi, auth()->id(), $request->admin_note);

        return back()->with('success', 'Prestasi berhasil disetujui.');
    }

    /**
     * Reject a prestasi.
     */
    public function reject(Request $request, Prestasi $prestasi)
    {
        $request->validate(['rejected_reason' => 'required|string|max:1000']);

        $this->prestasiService->reject($prestasi, auth()->id(), $request->rejected_reason);

        return back()->with('success', 'Prestasi berhasil ditolak.');
    }

    /**
     * Generate surat for a prestasi.
     */
    public function generateSurat(Request $request, Prestasi $prestasi)
    {
        $request->validate([
            'jenis_surat'           => 'required|string|in:' . implode(',', array_keys(Prestasi::JENIS_SURAT_LABELS)),
            'tanggal_surat'         => 'required|date',
            'penandatangan_nama'    => 'required|string|max:255',
            'penandatangan_jabatan' => 'required|string|max:255',
            'penandatangan_nip'     => 'nullable|string|max:100',
            'nomor_surat_manual'    => 'nullable|string|max:100',
            'lokasi_ttd'            => 'required|string|max:100',
        ]);

        try {
            $surat = $this->letterService->generateSurat(
                $prestasi,
                $request->jenis_surat,
                $request->tanggal_surat,
                $request->penandatangan_nama,
                $request->penandatangan_jabatan,
                $request->penandatangan_nip,
                $request->nomor_surat_manual,
                $request->lokasi_ttd,
                auth()->id()
            );

            // Log surat generation
            $this->prestasiService->logAction($prestasi, 'surat_generated', null, null, [
                'surat_id'    => $surat->id,
                'jenis_surat' => $request->jenis_surat,
                'nomor_surat' => $surat->nomor_surat,
            ]);

            // Auto-advance status if applicable
            $this->prestasiService->markSuratDiterbitkan($prestasi);

            return back()->with('success', 'Surat berhasil digenerate. Nomor: ' . $surat->nomor_surat);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal generate surat: ' . $e->getMessage());
        }
    }

    /**
     * Regenerate an existing surat PDF (re-render with current template).
     */
    public function regenerateSurat(Prestasi $prestasi, PrestasiSurat $surat)
    {
        try {
            $this->letterService->regenerateSurat($prestasi, $surat);

            return back()->with('success', 'Surat berhasil di-regenerate dengan template terbaru.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal regenerate surat: ' . $e->getMessage());
        }
    }

    /**
     * Download a surat PDF.
     */
    public function downloadSurat(Prestasi $prestasi, PrestasiSurat $surat)
    {
        if (!$surat->file_path) {
            abort(404, 'File surat tidak ditemukan.');
        }

        $disk = \App\Helpers\FileHelper::resolveDiskForPath($surat->file_path);
        if (!Storage::disk($disk)->exists($surat->file_path)) {
            abort(404, 'File surat tidak ditemukan di storage.');
        }

        $fileName = str_replace(' ', '_', $surat->jenis_surat_label) . '_' . $surat->nomor_surat . '.pdf';
        $fileName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $fileName);

        return Storage::disk($disk)->download($surat->file_path, $fileName);
    }

    /**
     * Preview a surat PDF inline.
     */
    public function previewSurat(Prestasi $prestasi, PrestasiSurat $surat)
    {
        if (!$surat->file_path) {
            abort(404, 'File surat tidak ditemukan.');
        }

        $disk = \App\Helpers\FileHelper::resolveDiskForPath($surat->file_path);
        if (!Storage::disk($disk)->exists($surat->file_path)) {
            abort(404, 'File surat tidak ditemukan di storage.');
        }

        return Storage::disk($disk)->response($surat->file_path);
    }

    /**
     * Verify sertifikat (pelaporan).
     */
    public function verifySertifikat(Request $request, Prestasi $prestasi)
    {
        $this->prestasiService->verifySertifikat($prestasi, auth()->id());

        return back()->with('success', 'Sertifikat berhasil diverifikasi.');
    }

    /**
     * Mark prestasi as selesai.
     */
    public function markSelesai(Prestasi $prestasi)
    {
        try {
            $this->prestasiService->markSelesai($prestasi);
            return back()->with('success', 'Prestasi berhasil ditandai selesai.');
        } catch (\LogicException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update surat settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.custom_code' => 'required|string|max:50|regex:/^[A-Z0-9\/]+$/',
            'settings.*.counter' => 'required|integer|min:0',
        ]);

        // Convert custom_code to full format
        $formattedSettings = [];
        foreach ($request->settings as $jenis => $data) {
            $formattedSettings[$jenis] = [
                'format' => '{counter}/' . $data['custom_code'] . '/{month}/{year}',
                'counter' => $data['counter'],
            ];
        }

        $this->letterService->updateSettings($formattedSettings);

        return back()->with('success', 'Pengaturan nomor surat berhasil disimpan.');
    }

    /**
     * AJAX: Preview nomor surat.
     */
    public function previewNomor(Request $request)
    {
        $jenisSurat = $request->jenis_surat ?? 'tugas';
        $tanggalSurat = $request->tanggal_surat ?? now()->format('Y-m-d');

        return response()->json([
            'nomor_surat' => $this->letterService->previewNomorSurat($jenisSurat, $tanggalSurat),
        ]);
    }

    /**
     * Export Excel.
     */
    public function exportExcel(Request $request)
    {
        $query = Prestasi::with(['pengaju.user', 'dosenPendamping.user', 'surats']);

        if ($request->filled('from_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('tanggal_mulai', '<=', $request->to_date);
        }

        $data = $query->orderByDesc('created_at')->get();

        $rows = [];
        $rows[] = ['No', 'Tipe', 'Nama Pengaju', 'Role', 'NIM/NIDN', 'Nama Kegiatan', 'Jenis', 'Tingkat', 'Tempat', 'Tanggal Mulai', 'Tanggal Selesai', 'Penyelenggara', 'Jenis Prestasi', 'Status', 'Nomor Surat'];

        foreach ($data as $i => $p) {
            $rows[] = [
                $i + 1,
                ucfirst($p->tipe),
                $p->pengaju_name,
                $p->pengaju_role,
                $p->pengaju_identifier,
                $p->nama_kegiatan,
                $p->jenis_kegiatan,
                $p->tingkat_label,
                $p->tempat_kegiatan,
                $p->tanggal_mulai?->format('d/m/Y'),
                $p->tanggal_selesai?->format('d/m/Y'),
                $p->penyelenggara,
                $p->jenis_prestasi,
                $p->status_label,
                $p->surats->pluck('nomor_surat')->join(', '),
            ];
        }

        // Generate CSV
        $output = fopen('php://temp', 'r+');
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="prestasi_export_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
