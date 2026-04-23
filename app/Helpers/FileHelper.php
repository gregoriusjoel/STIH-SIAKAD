<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * Resolve disk name from config or parameter
     * Default: s3local for local, s3 for cloud
     */
    /**
     * Resolve disk name based on file path
     * Paths starting with 'documents', 'private', or 'krs' use s3private (locally)
     */
    public static function resolveDiskForPath(string $path): string
    {
        $default = config('filesystems.default', 's3local');
        if ($default !== 's3local') {
            return $default;
        }

        if (str_starts_with($path, 'documents') || str_starts_with($path, 'private') || str_starts_with($path, 'krs')) {
            return 's3private';
        }

        return 's3local';
    }

    /**
     * Generate unique filename from original name
     * Returns: slug-timestamp-random.extension
     */
    public static function generateFilename(string $originalName): string
    {
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        
        $slug = Str::slug($name, '-');
        $unique = substr(md5(microtime()), 0, 8);
        
        return $slug . '-' . $unique . ($ext ? '.' . $ext : '');
    }

    /**
     * Upload file to storage
     * @param string $path Path/folder where file will be stored (e.g., "mahasiswa/123")
     * @param mixed $file Uploaded file or file content
     * @param string|null $disk Disk name (s3local, s3, etc.)
     * @return string|false Full path of stored file or false on error
     */
    public static function uploadFile(string $path, $file)
    {
        try {
            $disk = self::resolveDiskForPath($path);
            $filename = self::generateFilename($file->getClientOriginalName());
            $fullPath = trim($path, '/') . '/' . $filename;

            Storage::disk($disk)->putFileAs($path, $file, $filename);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('File upload failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Delete file from storage
     * @param string $path Full path to file
     * @param string|null $disk Disk name
     * @return bool
     */
    public static function deleteFile(string $path): bool
    {
        try {
            $disk = self::resolveDiskForPath($path);
            return Storage::disk($disk)->delete($path);
        } catch (\Exception $e) {
            \Log::error('File delete failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get public file URL
     * @param string $path File path in storage
     * @param string|null $disk Disk name
     * @return string|null Full URL or null if not exists
     */
    public static function fileUrl(string $path): ?string
    {
        try {
            $disk = self::resolveDiskForPath($path);
            
            if (!Storage::disk($disk)->exists($path)) {
                return null;
            }

            return Storage::disk($disk)->url($path);
        } catch (\Exception $e) {
            \Log::error('File URL generation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get private file signed URL (limited time access)
     * @param string $path File path in storage
     * @param int $expiredMinutes Expiration time in minutes
     * @return string|null Signed URL or null if not exists
     */
    public static function filePrivateUrl(string $path, int $expiredMinutes = 5): ?string
    {
        try {
            $disk = self::resolveDiskForPath($path);
            
            if (!Storage::disk($disk)->exists($path)) {
                return null;
            }

            return \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'files.private',
                now()->addMinutes($expiredMinutes),
                ['path' => base64_encode($path)]
            );
        } catch (\Exception $e) {
            \Log::error('Signed URL generation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check if file exists in storage
     * @param string $path File path
     * @param string|null $disk Disk name
     * @return bool
     */
    public static function fileExistsStorage(string $path): bool
    {
        try {
            $disk = self::resolveDiskForPath($path);
            return Storage::disk($disk)->exists($path);
        } catch (\Exception $e) {
            \Log::error('File exists check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
