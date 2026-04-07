<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Materi;
use App\Services\ActiveMeetingResolver;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function __construct(private FileStorageService $storage) {}

    /**
     * Resolve slot number from pertemuan route param (supports "kuliah:3", "uts:1", or plain int).
     */
    private function resolveSlotNumber($pertemuan): int
    {
        if (str_contains((string) $pertemuan, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuan, 2);
            return app(ActiveMeetingResolver::class)->tipeNomorToSlot($tipe, (int) $nomor);
        }
        return (int) $pertemuan;
    }

    /**
     * Display materials for a specific meeting.
     */
    public function index($id, $pertemuan)
    {
        $pertemuan = $this->resolveSlotNumber($pertemuan);
        $kelas = Kelas::with('mataKuliah')->findOrFail($id);

        // Materials are shared across all classes of the same mata kuliah
        $materis = Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $pertemuan)
            ->with('dosen')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'materis' => $materis,
        ]);
    }

    /**
     * Upload a new material to S3.
     */
    public function store(Request $request, $id, $pertemuan)
    {
        $pertemuan = $this->resolveSlotNumber($pertemuan);
        $kelas = Kelas::with('mataKuliah')->findOrFail($id);

        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file'      => 'required|file|mimes:pdf,docx,pptx,xls,xlsx,zip,rar|max:51200', // 50MB max
        ]);

        $file     = $request->file('file');
        $filePath = $this->storage->upload($file, 'documents/materi');

        Materi::create([
            'mata_kuliah_id' => $kelas->mata_kuliah_id,
            'dosen_id'       => Auth::user()->dosen->id ?? null,
            'pertemuan'      => $pertemuan,
            'judul'          => $validated['judul'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'file_path'      => $filePath,
            'file_name'      => $file->getClientOriginalName(),
            'file_type'      => $file->getClientOriginalExtension(),
            'file_size'      => $file->getSize(),
        ]);

        return redirect()->back()->with('success', 'Materi berhasil diunggah dan tersedia untuk semua kelas mata kuliah ini.');
    }

    /**
     * Delete a material and its S3 file.
     */
    public function destroy($id, $pertemuan, $materiId)
    {
        $materi = Materi::findOrFail($materiId);

        $this->storage->delete($materi->file_path);

        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Download a material from S3.
     */
    public function download($materiId)
    {
        $materi = Materi::findOrFail($materiId);

        if (!$materi->file_path || !$this->storage->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return $this->storage->download($materi->file_path, $materi->file_name);
    }
}
