<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Dosen;
use App\Models\PrestasiSurat;
use App\Services\PrestasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function __construct(
        private PrestasiService $prestasiService,
    ) {}

    /**
     * List prestasi milik dosen + yang didampingi.
     */
    public function index()
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen) abort(403);

        // Own prestasi
        $ownPrestasis = Prestasi::forDosen($dosen->id)
            ->with(['dosenPendamping.user', 'surats'])
            ->orderByDesc('updated_at')
            ->get();

        // Dampingan prestasi
        $dampinganPrestasis = Prestasi::dampingan($dosen->id)
            ->with(['pengaju.user', 'surats'])
            ->orderByDesc('updated_at')
            ->get();

        return view('page.dosen.prestasi.index', compact('ownPrestasis', 'dampinganPrestasis', 'dosen'));
    }

    /**
     * Create form.
     */
    public function create(Request $request)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen) abort(403);

        $tipe = $request->get('tipe', 'pengajuan');

        return view('page.dosen.prestasi.create', compact('dosen', 'tipe'));
    }

    /**
     * Store new prestasi.
     */
    public function store(Request $request)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen) abort(403);

        $validated = $request->validate([
            'tipe'             => 'required|in:pengajuan,pelaporan',
            'nama_kegiatan'    => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string|in:akademik,non-akademik',
            'tingkat_kegiatan' => 'required|in:internal,regional,nasional,internasional',
            'tempat_kegiatan'  => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'penyelenggara'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:2000',
            'jenis_prestasi'   => 'nullable|string|max:255',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'keterangan'       => 'nullable|string|max:2000',
        ]);

        try {
            $prestasi = $this->prestasiService->createDraft(
                Dosen::class,
                $dosen->id,
                collect($validated)->except(['sertifikat'])->toArray()
            );

            // Upload sertifikat if provided
            if ($request->hasFile('sertifikat')) {
                $this->prestasiService->uploadDokumen($prestasi, $request->file('sertifikat'), 'sertifikat', auth()->id());
            }

            // Upload dokumentasi if provided
            if ($request->hasFile('dokumentasi')) {
                foreach ($request->file('dokumentasi') as $file) {
                    $this->prestasiService->uploadDokumen($prestasi, $file, 'dokumentasi', auth()->id());
                }
            }

            return redirect()->route('dosen.prestasi.show', $prestasi)
                ->with('success', 'Prestasi berhasil disimpan sebagai draft.');
        } catch (\LogicException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show detail.
     */
    public function show(Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen) abort(403);

        // Allow access if own or dampingan
        $isOwn = ($prestasi->pengaju_type === Dosen::class && $prestasi->pengaju_id === $dosen->id);
        $isDampingan = ($prestasi->dosen_pendamping_id === $dosen->id);

        if (!$isOwn && !$isDampingan) abort(403);

        $prestasi->load(['pengaju.user', 'dosenPendamping.user', 'dokumens', 'logs.user', 'surats']);

        return view('page.dosen.prestasi.show', compact('prestasi', 'dosen', 'isOwn', 'isDampingan'));
    }

    /**
     * Edit form.
     */
    public function edit(Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen || $prestasi->pengaju_id !== $dosen->id || $prestasi->pengaju_type !== Dosen::class || !$prestasi->isEditable()) {
            abort(403);
        }

        return view('page.dosen.prestasi.edit', compact('prestasi', 'dosen'));
    }

    /**
     * Update.
     */
    public function update(Request $request, Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen || $prestasi->pengaju_id !== $dosen->id || $prestasi->pengaju_type !== Dosen::class) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string|in:akademik,non-akademik',
            'tingkat_kegiatan' => 'required|in:internal,regional,nasional,internasional',
            'tempat_kegiatan'  => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'penyelenggara'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:2000',
            'jenis_prestasi'   => 'nullable|string|max:255',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'keterangan'       => 'nullable|string|max:2000',
        ]);

        try {
            $this->prestasiService->updateData($prestasi, $validated);
            return redirect()->route('dosen.prestasi.show', $prestasi)
                ->with('success', 'Data prestasi berhasil diperbarui.');
        } catch (\LogicException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Submit to admin.
     */
    public function submit(Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen || $prestasi->pengaju_id !== $dosen->id || $prestasi->pengaju_type !== Dosen::class) {
            abort(403);
        }

        try {
            $this->prestasiService->submit($prestasi);
            return back()->with('success', 'Prestasi berhasil diajukan ke admin.');
        } catch (\LogicException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Upload dokumen tambahan.
     */
    public function uploadDokumen(Request $request, Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen || $prestasi->pengaju_id !== $dosen->id || $prestasi->pengaju_type !== Dosen::class) {
            abort(403);
        }

        $request->validate([
            'file'  => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'jenis' => 'required|in:sertifikat,dokumentasi,surat_tugas_lama,pendukung',
        ]);

        $this->prestasiService->uploadDokumen($prestasi, $request->file('file'), $request->jenis, auth()->id());

        return back()->with('success', 'Dokumen berhasil diupload.');
    }

    /**
     * Download surat.
     */
    public function downloadSurat(Prestasi $prestasi, PrestasiSurat $surat)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen) abort(403);

        $isOwn = ($prestasi->pengaju_type === Dosen::class && $prestasi->pengaju_id === $dosen->id);
        $isDampingan = ($prestasi->dosen_pendamping_id === $dosen->id);
        if (!$isOwn && !$isDampingan) abort(403);

        if (!$surat->file_path) abort(404);

        $disk = \App\Helpers\FileHelper::resolveDiskForPath($surat->file_path);
        if (!Storage::disk($disk)->exists($surat->file_path)) abort(404);

        return Storage::disk($disk)->download($surat->file_path, $surat->jenis_surat_label . '.pdf');
    }

    /**
     * Delete draft prestasi.
     */
    public function destroy(Prestasi $prestasi)
    {
        $dosen = auth()->user()->dosen;
        if (!$dosen || $prestasi->pengaju_id !== $dosen->id || $prestasi->pengaju_type !== Dosen::class) {
            abort(403);
        }

        try {
            $this->prestasiService->delete($prestasi);
            return redirect()->route('dosen.prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
        } catch (\LogicException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
