<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Centralises all S3 file operations for the application.
 * Use this service instead of calling Storage::disk('s3') directly.
 */
class FileStorageService
{
    public const DISK = 's3';

    /** MIME types treated as images. */
    private const IMAGE_MIMES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
    ];

    /** MIME types treated as documents. */
    private const DOCUMENT_MIMES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'text/csv',
    ];

    /**
     * Folder prefix used by the generic upload module.
     * images  → uploads/images
     * docs    → uploads/documents
     * others  → uploads/others
     */
    private const UPLOAD_FOLDER_MAP = [
        'image'    => 'uploads/images',
        'document' => 'uploads/documents',
        'other'    => 'uploads/others',
    ];

    // ──────────────────────────────────────────────────────────────
    //  Upload
    // ──────────────────────────────────────────────────────────────

    /**
     * Upload a file to S3.
     *
     * @param  UploadedFile  $file
     * @param  string|null   $folder  Override automatic folder detection.
     *                                Pass null to auto-detect (images/ vs documents/).
     * @return string  Relative path stored in DB (e.g. "images/uuid.jpg")
     */
    public function upload(UploadedFile $file, ?string $folder = null): string
    {
        $folder ??= $this->detectFolder($file);
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::uuid() . '.' . $extension;

        Storage::disk(self::DISK)->putFileAs($folder, $file, $filename);

        return $folder . '/' . $filename;
    }

    /**
     * Upload a file to S3 and persist an Upload model record.
     *
     * @param  UploadedFile  $file
     * @param  string|null   $folder   Override folder; null = auto-detect from mime.
     * @param  string|null   $label    Optional descriptive label.
     * @param  int|null      $userId   ID of the uploading user.
     * @return \App\Models\Upload
     */
    public function uploadAndRecord(
        UploadedFile $file,
        ?string      $folder = null,
        ?string      $label  = null,
        ?int         $userId = null,
    ): \App\Models\Upload {
        $detectedType = $this->detectType($file);   // 'image' | 'document' | 'other'
        $folder ??= self::UPLOAD_FOLDER_MAP[$detectedType];

        $extension = $file->getClientOriginalExtension();
        $filename  = Str::uuid() . '.' . $extension;
        $path      = $folder . '/' . $filename;

        Storage::disk(self::DISK)->putFileAs($folder, $file, $filename);

        return \App\Models\Upload::create([
            'user_id'       => $userId,
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'extension'     => strtolower($extension),
            'folder'        => $folder,
            'size'          => $file->getSize(),
            'disk'          => self::DISK,
            'label'         => $label,
        ]);
    }

    /**
     * Upload raw binary content (e.g. generated PDF/DOCX) to S3.
     *
     * @param  string  $path     Full relative path including filename.
     * @param  string  $content  Raw file content.
     */
    public function put(string $path, string $content): void
    {
        Storage::disk(self::DISK)->put($path, $content);
    }

    // ──────────────────────────────────────────────────────────────
    //  Delete
    // ──────────────────────────────────────────────────────────────

    /**
     * Safely delete a single file from S3.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Delete all files in a directory prefix on S3.
     */
    public function deleteDirectory(string $directory): void
    {
        Storage::disk(self::DISK)->deleteDirectory($directory);
    }

    // ──────────────────────────────────────────────────────────────
    //  URL / Download
    // ──────────────────────────────────────────────────────────────

    /**
     * Get the public URL for a stored file.
     */
    public function url(string $path): string
    {
        return Storage::disk(self::DISK)->url($path);
    }

    /**
     * Check whether a file exists on S3.
     */
    public function exists(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk(self::DISK)->exists($path);
    }

    /**
     * Stream a file download from S3 through the server.
     * Use this for private/protected documents.
     */
    public function download(string $path, ?string $name = null): StreamedResponse
    {
        $name ??= basename($path);

        return Storage::disk(self::DISK)->download($path, $name);
    }

    /**
     * Stream a file response (inline) from S3.
     */
    public function response(string $path, ?string $name = null): StreamedResponse
    {
        $name ??= basename($path);

        return Storage::disk(self::DISK)->response($path, $name);
    }

    // ──────────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Classify a file into one of three types: 'image' | 'document' | 'other'.
     */
    public function detectType(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        if (in_array($mime, self::IMAGE_MIMES, true) || str_starts_with($mime, 'image/')) {
            return 'image';
        }

        if (in_array($mime, self::DOCUMENT_MIMES, true)) {
            return 'document';
        }

        return 'other';
    }

    /**
     * Auto-detect whether a file belongs in images/ or documents/.
     * Used by the legacy upload() method that doesn't use the uploads/ prefix.
     */
    private function detectFolder(UploadedFile $file): string
    {
        return in_array($file->getMimeType(), self::IMAGE_MIMES, true)
            ? 'images'
            : 'documents';
    }

    /**
     * Return the canonical uploads/ sub-folder for a file.
     * e.g. 'uploads/images', 'uploads/documents', 'uploads/others'
     */
    public function resolveUploadFolder(UploadedFile $file): string
    {
        return self::UPLOAD_FOLDER_MAP[$this->detectType($file)];
    }
}
