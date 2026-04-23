# S3-Like Local Storage System - Usage Guide

## Overview
Sistem storage yang mengikuti konsep S3 AWS tapi menggunakan local filesystem. Clean, modular, dan easy to maintain.

## Struktur Penyimpanan
```
storage/app/s3/
├── public/          # Public files (direct access)
│   └── mahasiswa/
│       └── 123/
│           └── foto.jpg
└── private/         # Private files (signed URL only)
    └── dokumen/
        └── transkrip.pdf
```

## Konfigurasi
**Disk:** `s3local` (defined in `config/filesystems.php`)
**URL Base:** `/storage/s3/public/` (public), signed URL (private)

---

## Usage Examples

### 1. Upload File (Public)
```php
// Di controller atau service
use App\Helpers\FileHelper;

$file = $request->file('foto');
$path = FileHelper::uploadFile('mahasiswa/123', $file, 's3local');

// Returns: "mahasiswa/123/foto-abc123ef.jpg"
if ($path) {
    $url = FileHelper::fileUrl($path, 's3local');
    // Returns: "http://app.local/storage/s3/public/mahasiswa/123/foto-abc123ef.jpg"
}
```

### 2. Get Public File URL
```php
$url = FileHelper::fileUrl('mahasiswa/123/foto.jpg');
// No parameter = uses default disk (s3local or config)
// Returns: "http://app.local/storage/s3/public/mahasiswa/123/foto.jpg"
```

### 3. Upload & Store Path in Database
```php
// In MahasiswaController@store
$file = $request->file('foto');
$storagePath = FileHelper::uploadFile('mahasiswa', $file);

if ($storagePath) {
    $mahasiswa = Mahasiswa::create([
        'nim' => $request->nim,
        'foto_path' => $storagePath,
        // ... other fields
    ]);
}
```

### 4. Display in Blade
```blade
<!-- Public file -->
<img src="{{ FileHelper::fileUrl($mahasiswa->foto_path) }}" alt="Foto">

<!-- Or simpler with helper -->
@if ($mahasiswa->foto_path)
    <img src="{{ storage_url($mahasiswa->foto_path) }}" alt="Foto">
@endif
```

### 5. Private File (Signed URL)
```php
// Generate 5-minute signed URL
$signedUrl = FileHelper::filePrivateUrl('dokumen/transkrip.pdf', 5);
// Returns: "http://app.local/storage/private?disk=s3local&path=base64_encoded&expires=timestamp"

// Can be sent via email or used in link with limited time access
<a href="{{ $signedUrl }}">Download Transkrip (expires in 5 minutes)</a>
```

### 6. Check File Exists
```php
if (FileHelper::fileExistsStorage('mahasiswa/123/foto.jpg')) {
    $url = FileHelper::fileUrl('mahasiswa/123/foto.jpg');
}
```

### 7. Delete File
```php
// Delete single file
FileHelper::deleteFile('mahasiswa/123/foto.jpg');

// Or when updating
$oldPath = $mahasiswa->foto_path;
$newPath = FileHelper::uploadFile('mahasiswa', $request->file('foto'));

if ($newPath && FileHelper::fileExistsStorage($oldPath)) {
    FileHelper::deleteFile($oldPath);
}
```

### 8. Use Different Disk
```php
// Upload to S3 AWS (if configured)
$path = FileHelper::uploadFile('dokumen', $file, 's3');

// Get URL from specific disk
$url = FileHelper::fileUrl($path, 's3');
```

---

## Helper Functions

### `uploadFile($path, $file, $disk = null)`
- **Path:** Folder path (e.g., "mahasiswa/123")
- **File:** Uploaded file object
- **Disk:** Optional, defaults to config('filesystems.default')
- **Returns:** Full file path or `false` on error
- **Generates:** Auto unique filename (slug + md5 hash + extension)

### `deleteFile($path, $disk = null)`
- **Path:** Full file path
- **Disk:** Optional
- **Returns:** `true` or `false`

### `fileUrl($path, $disk = null)`
- **Path:** Full file path
- **Disk:** Optional
- **Returns:** Public URL or `null` if not exists

### `filePrivateUrl($path, $expiredMinutes = 5, $disk = null)`
- **Path:** Full file path
- **ExpiredMinutes:** URL validity duration
- **Disk:** Optional
- **Returns:** Signed URL or `null` if not exists

### `fileExistsStorage($path, $disk = null)`
- **Path:** Full file path
- **Disk:** Optional
- **Returns:** `true` or `false`

### `resolveDisk($disk = null)`
- Internal helper to resolve disk name
- Returns default disk if not specified

### `generateFilename($originalName)`
- Internal helper to generate unique filename
- Format: `slug-md5hash.ext`
- Prevents filename collisions

---

## Switching from AWS S3 to Local

### Current (AWS S3)
```php
// .env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_BUCKET=...
```

### Switch to Local S3-Like
```php
// .env
FILESYSTEM_DISK=s3local
# Remove AWS credentials if not needed
```

**Code:** No changes needed! All functions use config-aware disk resolution.

### Switch Back to AWS (Zero Code Changes)
```php
// .env
FILESYSTEM_DISK=s3
# Add AWS credentials back
```

---

## Best Practices

### ✅ DO
- Use meaningful folder paths: `mahasiswa/{id}/`, `dokumen/transkrip/`
- Store file path in database (not duplicate the file)
- Use private URLs for sensitive files
- Check file exists before serving
- Log errors (helper does this automatically)
- Use unique filenames (helper generates automatically)

### ❌ DON'T
- Hardcode `storage_path()` in code
- Expose private folder in URL
- Store large binary data in database
- Forget to delete old files on update
- Use raw `Storage::disk()` calls everywhere
- Trust user-provided filenames

---

## Security Notes

### Public Files
- Direct URL access: `/storage/s3/public/...`
- No authentication needed
- Safe for logos, avatars, etc.

### Private Files
- Signed URL with timestamp validation
- Limited validity period (default 5 min)
- Path traversal protection (`.., /` blocked)
- Route requires middleware if needed

### File Upload
- Accept file from request (Laravel validates)
- Auto-generate filename (no user control)
- Store path reference in DB
- Delete old file on update

---

## Performance Tips

### Large Files
```php
// For large uploads, consider chunking via frontend
// Then combine server-side
$path = FileHelper::uploadFile('large-files', $chunk);
```

### Multiple Disks
```php
// Archive old files to different disk
$archivePath = FileHelper::uploadFile(
    'archive/2024',
    $file,
    's3' // Archive disk
);

// Keep active in local s3local
```

### Cleanup Job
```php
// Periodic cleanup of orphaned files
// Use filesystem:cleanup artisan command
// Or create scheduled task
```

---

## Troubleshooting

### File not found
```php
// Check if exists first
if (!FileHelper::fileExistsStorage($path)) {
    // Handle missing file
}
```

### URL returns null
```php
// Check disk configuration
// Check folder exists: storage/app/s3/public/
// Check symbolic link: php artisan storage:link
```

### Storage:link command fails
```bash
# Verify storage directories exist
ls -la storage/app/s3/public
ls -la storage/app/s3/private

# Re-run link
php artisan storage:link --force
```

### Signed URL expired
```php
// Generate new URL with longer expiration
$url = FileHelper::filePrivateUrl($path, 60); // 60 minutes
```

---

## Environment Variables

### .env Configuration
```bash
# Default disk (s3local or s3)
FILESYSTEM_DISK=s3local

# Public app URL (used for storage URLs)
APP_URL=http://localhost:8000

# For AWS S3 (if using s3 disk)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

---

Generated: 2024-04-23 | Laravel 12 | S3-Like Local Storage System
