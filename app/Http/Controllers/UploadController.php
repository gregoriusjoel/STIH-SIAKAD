<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * UploadController
 * ─────────────────────────────────────────────────────────────────
 * Handles generic file uploads to S3.
 *
 * Routes:
 *   GET  /uploads              → index (gallery/list)
 *   POST /uploads              → store (single or multiple files)
 *   GET  /uploads/{upload}     → show (detail)
 *   DELETE /uploads/{upload}   → destroy (delete from S3 + DB)
 */
class UploadController extends Controller
{
    public function __construct(private FileStorageService $storage) {}

    // ── Index ───────────────────────────────────────────────────────────

    /**
     * Gallery page — show all uploads for the current user.
     */
    public function index()
    {
        $uploads = Upload::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('uploads.index', compact('uploads'));
    }

    // ── Store ───────────────────────────────────────────────────────────

    /**
     * Handle single or multiple file uploads.
     *
     * Accepts either `file` (single) or `files[]` (multiple).
     * Auto-detects S3 folder: uploads/images | uploads/documents | uploads/others
     */
    public function store(Request $request)
    {
        // Support both single (file) and multiple (files[]) input names
        $isSingle = $request->hasFile('file');
        $files    = $isSingle
            ? [$request->file('file')]
            : ($request->file('files') ?? []);

        if (empty($files)) {
            return response()->json(['error' => 'Tidak ada file yang diunggah.'], 422);
        }

        // Validate all files
        $request->validate(
            $isSingle
                ? ['file'    => 'required|file|max:5120']
                : ['files'   => 'required|array|min:1|max:10',
                   'files.*' => 'required|file|max:5120'],
            [
                'file.max'    => 'Ukuran file maksimal 5 MB.',
                'files.*.max' => 'Setiap file maksimal 5 MB.',
                'files.max'   => 'Maksimal 10 file sekaligus.',
            ]
        );

        $uploaded = [];

        foreach ($files as $file) {
            $record     = $this->storage->uploadAndRecord(
                file:   $file,
                folder: null,   // auto-detect based on mime type
                label:  $request->input('label'),
                userId: Auth::id(),
            );

            $uploaded[] = [
                'id'            => $record->id,
                'file_path'     => $record->file_path,
                'url'           => $record->url,
                'original_name' => $record->original_name,
                'mime_type'     => $record->mime_type,
                'is_image'      => $record->is_image,
                'human_size'    => $record->human_size,
                'folder'        => $record->folder,
            ];
        }

        // Return JSON for AJAX requests, redirect otherwise
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => count($uploaded) . ' file berhasil diunggah ke S3.',
                'uploads' => $uploaded,
            ]);
        }

        return redirect()->route('uploads.index')
            ->with('success', count($uploaded) . ' file berhasil diunggah.');
    }

    // ── Show ────────────────────────────────────────────────────────────

    public function show(Upload $upload)
    {
        $this->authorizeUpload($upload);

        return response()->json([
            'id'            => $upload->id,
            'url'           => $upload->url,
            'original_name' => $upload->original_name,
            'mime_type'     => $upload->mime_type,
            'is_image'      => $upload->is_image,
            'human_size'    => $upload->human_size,
            'folder'        => $upload->folder,
            'label'         => $upload->label,
            'created_at'    => $upload->created_at->toIso8601String(),
        ]);
    }

    // ── Destroy ─────────────────────────────────────────────────────────

    /**
     * Delete a file from S3 and remove its DB record.
     * S3 deletion is handled automatically by Upload::boot() deleting event.
     */
    public function destroy(Upload $upload)
    {
        $this->authorizeUpload($upload);

        $upload->delete(); // triggers S3 delete via boot()

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus.',
            ]);
        }

        return redirect()->route('uploads.index')
            ->with('success', 'File berhasil dihapus.');
    }

    // ── Private ─────────────────────────────────────────────────────────

    /**
     * Ensure the current user owns this upload.
     */
    private function authorizeUpload(Upload $upload): void
    {
        if ($upload->user_id && $upload->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
    }
}
