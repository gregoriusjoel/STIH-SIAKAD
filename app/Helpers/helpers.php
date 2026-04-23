<?php

/**
 * Global Helper Functions for Storage
 * Provides convenient access to FileHelper from Blade templates
 */

use App\Helpers\FileHelper;

if (!function_exists('storage_url')) {
    /**
     * Get public file URL
     * Usage in Blade: {{ storage_url($path) }}
     */
    function storage_url(string $path, ?string $disk = null): ?string
    {
        return FileHelper::fileUrl($path, $disk);
    }
}

if (!function_exists('file_private_url')) {
    /**
     * Get private file signed URL
     * Usage in Blade: {{ file_private_url($path, 10) }}
     */
    function file_private_url(string $path, int $expiredMinutes = 5): ?string
    {
        return App\Helpers\FileHelper::filePrivateUrl($path, $expiredMinutes);
    }
}

if (!function_exists('storage_exists')) {
    /**
     * Check if file exists in storage
     * Usage: @if (storage_exists($path)) ... @endif
     */
    function storage_exists(string $path, ?string $disk = null): bool
    {
        return FileHelper::fileExistsStorage($path, $disk);
    }
}
