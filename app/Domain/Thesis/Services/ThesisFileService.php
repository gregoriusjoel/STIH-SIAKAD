<?php

namespace App\Domain\Thesis\Services;

use App\Models\Mahasiswa;
use App\Models\ThesisSidangRegistration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Manages all file storage for the thesis module.
 * All paths are relative to the storage/app/private disk unless noted.
 */
class ThesisFileService
{
    public const DISK = 'local'; // change to 's3' for cloud

    public function storeProposal(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->id}/proposal", self::DISK);
    }

    public function storeGuidanceFile(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->id}/bimbingan", self::DISK);
    }

    public function storeSidangFile(Mahasiswa $mahasiswa, string $type, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->id}/sidang/{$type}", self::DISK);
    }

    public function storeRevision(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->id}/revisi", self::DISK);
    }

    /**
     * Build all files for the sidang registration from the request.
     * Returns array suitable for ThesisSidangFile::insert().
     */
    public function processSidangFiles(
        ThesisSidangRegistration $reg,
        Mahasiswa $mahasiswa,
        array $files // ['file_type' => UploadedFile]
    ): array {
        $records = [];

        foreach ($files as $type => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeSidangFile($mahasiswa, $type, $file);

            $records[] = [
                'sidang_registration_id' => $reg->id,
                'file_type'              => $type,
                'file_path'              => $path,
                'original_name'          => $file->getClientOriginalName(),
                'file_size'              => $file->getSize(),
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        return $records;
    }

    /**
     * Delete a file from storage.
     */
    public function delete(string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Get a temporary URL or stream path for private files.
     */
    public function downloadResponse(string $path, string $name): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk(self::DISK)->download($path, $name);
    }
}
