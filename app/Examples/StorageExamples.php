<?php

/**
 * EXAMPLE USAGE - S3-Like Local Storage System
 * 
 * File ini menunjukkan bagaimana menggunakan FileHelper di berbagai skenario
 * Praktik best practice untuk project STIH-SIAKAD
 */

namespace App\Examples;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;

class StorageExamples
{
    /**
     * EXAMPLE 1: Upload Foto Mahasiswa (Controller)
     */
    public function uploadMahasiswaFoto(Request $request)
    {
        $file = $request->file('foto');
        
        // Upload file
        $path = FileHelper::uploadFile("mahasiswa/{$request->user_id}", $file);
        
        if ($path) {
            // Simpan path ke database
            $mahasiswa->update(['foto_path' => $path]);
            
            // Get public URL untuk ditampilkan
            $url = FileHelper::fileUrl($path);
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $url,
            ]);
        }
        
        return response()->json(['success' => false, 'error' => 'Upload failed'], 400);
    }

    /**
     * EXAMPLE 2: Display Foto di Blade Template
     */
    public function showMahasiswa()
    {
        $mahasiswa = \App\Models\Mahasiswa::find(1);
        
        // Dalam controller, bisa kirim URL langsung
        // Atau handle di blade dengan helper
        
        return view('mahasiswa.show', ['mahasiswa' => $mahasiswa]);
        
        // Di blade.php:
        // @if ($mahasiswa->foto_path && storage_exists($mahasiswa->foto_path))
        //     <img src="{{ storage_url($mahasiswa->foto_path) }}" alt="Foto">
        // @endif
    }

    /**
     * EXAMPLE 3: Update Foto (Delete Lama + Upload Baru)
     */
    public function updateMahasiswaFoto(Request $request, $id)
    {
        $mahasiswa = \App\Models\Mahasiswa::find($id);
        
        // Delete file lama jika ada
        if ($mahasiswa->foto_path) {
            FileHelper::deleteFile($mahasiswa->foto_path);
        }
        
        // Upload file baru
        $newPath = FileHelper::uploadFile("mahasiswa/{$id}", $request->file('foto'));
        
        if ($newPath) {
            $mahasiswa->update(['foto_path' => $newPath]);
            return back()->with('success', 'Foto berhasil diperbarui');
        }
        
        return back()->with('error', 'Gagal upload foto');
    }

    /**
     * EXAMPLE 4: Download Dokumen Privat dengan Signed URL
     */
    public function downloadDokumen($dosenId)
    {
        $dosen = \App\Models\Dosen::find($dosenId);
        
        if (!$dosen->sertifikat_path) {
            return back()->with('error', 'Dokumen tidak ditemukan');
        }
        
        // Generate 10-minute signed URL
        $url = FileHelper::filePrivateUrl(
            $dosen->sertifikat_path,
            10  // 10 minutes
        );
        
        return view('dokumen.download', ['url' => $url]);
    }

    /**
     * EXAMPLE 5: Bulk Upload (Import Data)
     */
    public function importMahasiswa(Request $request)
    {
        $file = $request->file('import_file');
        
        // Simpan file import untuk referensi
        $importPath = FileHelper::uploadFile('import/mahasiswa', $file);
        
        if (!$importPath) {
            return back()->with('error', 'File upload failed');
        }
        
        // Proses import...
        // $data = Excel::import(new MahasiswaImport, $importPath);
        
        return back()->with('success', 'Import berhasil');
    }

    /**
     * EXAMPLE 6: Backup File
     */
    public function backupData()
    {
        $backupFile = 'backup_' . now()->format('Y-m-d_His') . '.zip';
        
        // Simpan backup ke folder archive
        $path = FileHelper::uploadFile('backups', $backupFile);
        
        if ($path) {
            \Log::info("Backup created at: {$path}");
            return response()->json(['success' => true, 'path' => $path]);
        }
    }

    /**
     * EXAMPLE 7: Conditional Storage (Public vs Private)
     */
    public function handleDocument(Request $request)
    {
        $file = $request->file('document');
        $isPublic = $request->boolean('is_public');
        
        if ($isPublic) {
            // Public document
            $path = FileHelper::uploadFile('dokumen/public', $file);
            $url = FileHelper::fileUrl($path);
            
            return response()->json([
                'path' => $path,
                'url' => $url,
                'type' => 'public'
            ]);
        } else {
            // Private document
            $path = FileHelper::uploadFile('dokumen/private', $file);
            
            // Generate signed URL dengan 30 menit validity
            $signedUrl = FileHelper::filePrivateUrl($path, 30);
            
            return response()->json([
                'path' => $path,
                'url' => $signedUrl,
                'type' => 'private'
            ]);
        }
    }

    /**
     * EXAMPLE 8: Switch Disk (Local vs AWS)
     */
    public function handleMultiDisk(Request $request)
    {
        $file = $request->file('document');
        $useCloud = env('USE_S3_CLOUD', false);
        
        $disk = $useCloud ? 's3' : 's3local';
        
        $path = FileHelper::uploadFile('documents', $file, $disk);
        $url = FileHelper::fileUrl($path, $disk);
        
        return response()->json([
            'disk' => $disk,
            'path' => $path,
            'url' => $url
        ]);
    }

    /**
     * EXAMPLE 9: Validate File Exists Sebelum Serve
     */
    public function viewDocument($id)
    {
        $document = \App\Models\Document::find($id);
        
        if (!$document || !FileHelper::fileExistsStorage($document->path)) {
            abort(404, 'Document not found');
        }
        
        $url = FileHelper::fileUrl($document->path);
        
        return view('document.view', [
            'document' => $document,
            'url' => $url
        ]);
    }

    /**
     * EXAMPLE 10: Blade Template Best Practices
     */
    public function bladeExamples()
    {
        // Contoh di blade:
        
        // 1. Simple display
        // <img src="{{ storage_url($user->avatar_path) }}" />
        
        // 2. With exists check
        // @if (storage_exists($user->avatar_path))
        //     <img src="{{ storage_url($user->avatar_path) }}" />
        // @else
        //     <img src="/images/default-avatar.png" />
        // @endif
        
        // 3. Private file download link
        // <a href="{{ storage_private_url($document->path, 30) }}">
        //     Download (expires in 30 min)
        // </a>
        
        // 4. Loop multiple files
        // @foreach ($documents as $doc)
        //     <a href="{{ storage_private_url($doc->path) }}">
        //         {{ $doc->name }}
        //     </a>
        // @endforeach
    }
}

/**
 * STORAGE STRUCTURE PATTERNS
 * 
 * Pattern 1: By Resource Type
 * mahasiswa/
 * ├── 1/
 * │   ├── foto.jpg
 * │   └── dokumen/
 * │       └── transkrip.pdf
 * ├── 2/
 * └── 3/
 * 
 * Pattern 2: By Category
 * dokumen/
 * ├── transkrip/
 * ├── sertifikat/
 * └── ijazah/
 * 
 * Pattern 3: Flat with Timestamps
 * uploads/2024/04/23/foto-abc123.jpg
 * uploads/2024/04/23/dokumen-def456.pdf
 * 
 * RECOMMENDATION: Gunakan Pattern 1 (By Resource Type)
 * - Easier to manage & cleanup
 * - Better organization
 * - Easy to link with database IDs
 */

/**
 * NAMING CONVENTION
 * 
 * Filenames generated oleh FileHelper:
 * - nama-slug dari original filename
 * - 8-char MD5 hash untuk uniqueness
 * - extension asli
 * 
 * Example:
 * Input:  "John Doe Photo (1).jpg"
 * Output: "john-doe-photo-1-abc123ef.jpg"
 * 
 * Benefits:
 * ✓ Prevents overwrite
 * ✓ Sanitized filename
 * ✓ Safe for filesystem
 * ✓ URL-friendly
 */

/**
 * ERROR HANDLING
 * 
 * FileHelper always returns safely:
 * - uploadFile(): returns false on error
 * - deleteFile(): returns false on error
 * - fileUrl(): returns null if not exists
 * - filePrivateUrl(): returns null if not exists
 * - fileExistsStorage(): returns false on error
 * 
 * All errors are logged automatically
 * Check logs/laravel.log for details
 */

/**
 * PERFORMANCE CONSIDERATIONS
 * 
 * 1. Large Files
 *    - Use chunked uploads from frontend
 *    - Keep file sizes reasonable
 * 
 * 2. Many Files
 *    - Use partition folders: storage/app/s3/{year}/{month}/{day}/
 *    - Prevents too many files in one directory
 * 
 * 3. URL Generation
 *    - Cache URLs di database atau cache layer
 *    - Jangan regenerate URL setiap request
 * 
 * 4. Cleanup
 *    - Periodic cleanup untuk orphaned files
 *    - Implement soft delete + cleanup job
 */
