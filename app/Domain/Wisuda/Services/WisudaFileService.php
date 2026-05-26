<?php

namespace App\Domain\Wisuda\Services;

use App\Models\Mahasiswa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Manages all file storage for the wisuda module.
 * Follows the same pattern as SkripsiFileService.
 */
class WisudaFileService
{
    public const DISK = 's3';

    public function storeDocument(Mahasiswa $mahasiswa, string $type, UploadedFile $file): string
    {
        return $file->store("wisuda/{$mahasiswa->id}/{$type}", self::DISK);
    }

    public function delete(string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    public function downloadResponse(string $path, string $name): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk(self::DISK)->download($path, $name);
    }

    public function previewResponse(string $path): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $disk     = Storage::disk(self::DISK);
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return response()->stream(function () use ($disk, $path) {
            $stream = $disk->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }
}
