<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Mahasiswa;
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
     * List prestasi milik mahasiswa.
     */
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa) abort(403);

        $prestasis = Prestasi::forMahasiswa($mahasiswa->id)
            ->with(['dosenPendamping.user', 'surats'])
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('page.mahasiswa.prestasi.index', compact('prestasis', 'mahasiswa'));
    }

    /**
     * Form create pengajuan/pelaporan.
     */
    public function create(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa) abort(403);

        $tipe = $request->get('tipe', 'pengajuan');
        $dosens = Dosen::with('user')->whereHas('user')->get();

        return view('page.mahasiswa.prestasi.create', compact('mahasiswa', 'tipe', 'dosens'));
    }

    /**
     * Store new prestasi.
     */
    public function store(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa) abort(403);

        $rules = [
            'tipe'             => 'required|in:pengajuan,pelaporan',
            'nama_kegiatan'    => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string|in:akademik,non-akademik',
            'tingkat_kegiatan' => 'required|in:internal,regional,nasional,internasional',
            'tempat_kegiatan'  => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'penyelenggara'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string|max:2000',
            'dosen_pendamping_id' => 'nullable|exists:dosens,id',
            'jenis_prestasi'   => 'nullable|string|max:255',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'keterangan'       => 'nullable|string|max:2000',
        ];

        // For pelaporan, sertifikat is required
        if ($request->tipe === 'pelaporan') {
            $rules['sertifikat'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
        }

        $validated = $request->validate($rules);

        try {
            $prestasi = $this->prestasiService->createDraft(
                Mahasiswa::class,
                $mahasiswa->id,
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

            // Upload surat tugas lama if provided
            if ($request->hasFile('surat_tugas_lama')) {
                $this->prestasiService->uploadDokumen($prestasi, $request->file('surat_tugas_lama'), 'surat_tugas_lama', auth()->id());
            }

            return redirect()->route('mahasiswa.prestasi.show', $prestasi)
                ->with('success', 'Prestasi berhasil disimpan sebagai draft.');
        } catch (\LogicException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show detail prestasi.
     */
    public function show(Prestasi $prestasi)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || ($prestasi->pengaju_type !== Mahasiswa::class || $prestasi->pengaju_id !== $mahasiswa->id)) {
            abort(403);
        }

        $prestasi->load(['dosenPendamping.user', 'dokumens', 'logs.user', 'surats']);

        return view('page.mahasiswa.prestasi.show', compact('prestasi', 'mahasiswa'));
    }

    /**
     * Edit form.
     */
    public function edit(Prestasi $prestasi)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id || !$prestasi->isEditable()) {
            abort(403);
        }

        $dosens = Dosen::with('user')->whereHas('user')->get();

        return view('page.mahasiswa.prestasi.edit', compact('prestasi', 'mahasiswa', 'dosens'));
    }

    /**
     * Update prestasi.
     */
    public function update(Request $request, Prestasi $prestasi)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id) {
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
            'dosen_pendamping_id' => 'nullable|exists:dosens,id',
            'jenis_prestasi'   => 'nullable|string|max:255',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'keterangan'       => 'nullable|string|max:2000',
        ]);

        try {
            $this->prestasiService->updateData($prestasi, $validated);

            return redirect()->route('mahasiswa.prestasi.show', $prestasi)
                ->with('success', 'Data prestasi berhasil diperbarui.');
        } catch (\LogicException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Submit prestasi to admin.
     */
    public function submit(Prestasi $prestasi)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id) {
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
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id) {
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
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id) {
            abort(403);
        }

        if (!$surat->file_path) abort(404);

        $disk = \App\Helpers\FileHelper::resolveDiskForPath($surat->file_path);
        if (!Storage::disk($disk)->exists($surat->file_path)) abort(404);

        return Storage::disk($disk)->download($surat->file_path, $surat->jenis_surat_label . '.pdf');
    }

    /**
     * Delete draft/rejected prestasi.
     */
    public function destroy(Prestasi $prestasi)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        if (!$mahasiswa || $prestasi->pengaju_id !== $mahasiswa->id) {
            abort(403);
        }

        try {
            $this->prestasiService->delete($prestasi);
            return redirect()->route('mahasiswa.prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
        } catch (\LogicException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
