<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateFileController extends Controller
{
    /**
     * Handle private file access with signature and authorization.
     * GET /files/private
     */
    public function __invoke(Request $request): StreamedResponse
    {
        // 1. Validate Signature (Laravel builtin)
        if (!$request->hasValidSignature()) {
            \Log::warning('Private file access: Invalid signature', [
                'user_id' => $request->user()?->id,
                'path' => $request->query('path')
            ]);
            abort(403, 'Tautan kedaluwarsa atau tidak valid.');
        }

        $path = base64_decode($request->query('path', ''));
        $disk = FileHelper::resolveDiskForPath($path);

        // 2. Validate File Existence
        if (!Storage::disk($disk)->exists($path)) {
            \Log::error('Private file access: File not found', [
                'disk' => $disk,
                'path' => $path,
                'absolute_path' => Storage::disk($disk)->path($path)
            ]);
            abort(404, 'File tidak ditemukan.');
        }

        // 3. Authorization Check
        try {
            $this->authorizeAccess($path, $request->user());
        } catch (\Exception $e) {
            \Log::warning('Private file access: Unauthorized', [
                'user_role' => $request->user()?->role,
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }

        \Log::info('Private file access: Success', [
            'user_id' => $request->user()?->id,
            'path' => $path
        ]);

        // 4. Return Streamed Response
        return Storage::disk($disk)->response($path);
    }

    /**
     * Check if user is authorized to access the specific file.
     */
    private function authorizeAccess(string $path, $user): void
    {
        // Admin has full access
        if ($user->role === 'admin') {
            return;
        }

        // Example: Check if student is accessing their own documents or KRS
        // Path pattern: documents/mahasiswa/{NIM}/... or krs/*_{NIM}/...
        if ($user->role === 'mahasiswa') {
            $mahasiswa = $user->mahasiswa;
            if ($mahasiswa) {
                // Check documents folder (new format: storage_folder, old format: nim)
                if (str_contains($path, 'documents/mahasiswa/' . $mahasiswa->storage_folder) || 
                    str_contains($path, 'documents/mahasiswa/' . $mahasiswa->nim)) {
                    return;
                }
                
                // Check KRS folder (new format: storage_folder, old format: id)
                if (str_starts_with($path, 'krs/') && (
                    str_contains($path, '/' . $mahasiswa->storage_folder . '/') || 
                    str_contains($path, '/' . $mahasiswa->id . '/')
                )) {
                    return;
                }

                // Check Internship folder
                if (str_starts_with($path, 'internship/') && str_contains($path, $mahasiswa->storage_folder)) {
                    return;
                }

                // Check Thesis folder
                if (str_starts_with($path, 'skripsi/') && str_contains($path, $mahasiswa->storage_folder)) {
                    return;
                }

                // Check Pengajuan folder
                if (str_starts_with($path, 'pengajuan/') && str_contains($path, $mahasiswa->storage_folder)) {
                    return;
                }

                // Or if it's their own photo in private
                if (str_contains($path, 'images/mahasiswa/foto') && $mahasiswa->foto === $path) {
                    return;
                }
            }
        }

        // Example: Dosen access (if needed)
        if ($user->role === 'dosen') {
            // Add dosen specific logic here
            // e.g. access to documents of their students
            return;
        }

        // Keuangan (Finance) role access
        if ($user->role === 'keuangan') {
            // Can access payment proofs and installment documents
            if (str_contains($path, 'documents/payment-proofs') || 
                str_contains($path, 'documents/installments')) {
                return;
            }
        }

        abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
    }
}
