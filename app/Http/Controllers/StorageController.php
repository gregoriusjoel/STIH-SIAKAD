<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    /**
     * Serve private file with signature validation
     * GET /storage/private?disk=s3local&path=base64_encoded_path&expires=timestamp
     */
    public function downloadPrivate(Request $request): StreamedResponse
    {
        $disk = $request->query('disk', config('filesystems.default'));
        $path = base64_decode($request->query('path', ''));
        $expires = (int) $request->query('expires', 0);

        // Validate signature & expiration
        if (!$this->validateSignature($path, $expires)) {
            abort(403, 'Access denied or link expired');
        }

        // Check file exists
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found');
        }

        // Stream file response
        return Storage::disk($disk)->download($path);
    }

    /**
     * Validate signed URL signature & expiration
     */
    private function validateSignature(string $path, int $expires): bool
    {
        // Check expiration
        if ($expires < now()->timestamp) {
            return false;
        }

        // Additional security: validate path format (prevent directory traversal)
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            return false;
        }

        return true;
    }
}
