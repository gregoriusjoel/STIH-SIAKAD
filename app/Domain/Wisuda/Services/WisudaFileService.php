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
    public function getDisk(): string
    {
        return config('filesystems.default', 's3local');
    }

    public function storeDocument(Mahasiswa $mahasiswa, string $type, UploadedFile $file): string
    {
        return $file->store("wisuda/{$mahasiswa->id}/{$type}", $this->getDisk());
    }

    public function delete(string $path): void
    {
        if ($path && Storage::disk($this->getDisk())->exists($path)) {
            Storage::disk($this->getDisk())->delete($path);
        }
    }

    public function downloadResponse(string $path, string $name): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk($this->getDisk())->download($path, $name);
    }

    public function previewResponse(string $path): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $disk     = Storage::disk(self::getDisk());
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
