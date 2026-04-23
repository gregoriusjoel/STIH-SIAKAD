# S3-Like Local Storage System - Architecture & Workflow

## System Overview

```
┌─────────────────────────────────────────────────────────┐
│                    APPLICATION                          │
├─────────────────────────────────────────────────────────┤
│  Controller/Service                                     │
│  ├─ FileHelper::uploadFile()                           │
│  ├─ FileHelper::deleteFile()                           │
│  ├─ FileHelper::fileUrl()                              │
│  └─ FileHelper::filePrivateUrl()                       │
└──────────────┬──────────────────────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────────────────────┐
│         Laravel Filesystem (Storage Facade)            │
├─────────────────────────────────────────────────────────┤
│  Storage::disk('s3local') or Storage::disk('s3')       │
└──────────────┬──────────────────────────────────────────┘
               │
      ┌────────┴────────┐
      ▼                 ▼
  ┌─────────┐     ┌──────────────────┐
  │ s3local │     │ s3 (AWS)         │
  │ (Local) │     │ (Cloud)          │
  └─────────┘     └──────────────────┘
      │                │
      ▼                ▼
storage/app/s3/    AWS S3
├── public/         Bucket
├── private/
└── ...
```

## File Upload Flow

```
User uploads file
       │
       ▼
Request::file('photo')
       │
       ▼
FileHelper::uploadFile('mahasiswa/123', $file)
       │
       ▼
generateFilename('photo.jpg') → 'photo-abc123ef.jpg'
       │
       ▼
Storage::disk('s3local')->putFileAs(...)
       │
       ▼
storage/app/s3/public/mahasiswa/123/photo-abc123ef.jpg
       │
       ▼
Save path to DB: 'mahasiswa/123/photo-abc123ef.jpg'
       │
       ▼
✅ Complete
```

## Public File Access Flow

```
Browser requests:
http://app.local/storage/s3/public/mahasiswa/123/photo-abc123ef.jpg
       │
       ▼
Via symlink: public/storage/s3 → storage/app/s3/public
       │
       ▼
Nginx/Apache serves static file
       │
       ▼
✅ Direct access (no PHP code executed)
```

## Private File Access Flow

```
Browser requests:
http://app.local/storage/private?disk=s3local&path=base64_encoded&expires=timestamp
       │
       ▼
Route → StorageController@downloadPrivate
       │
       ▼
Validate signature:
├─ Check expiration < now()
└─ Check path doesn't contain ../
       │
       ▼
FileHelper::fileExistsStorage($path)
       │
       ▼
Storage::disk('s3local')->download($path)
       │
       ▼
Stream file to browser
       │
       ▼
✅ Secure access with limited time
```

## File Naming Strategy

```
Original Filename: "John Doe's Photo (1).jpg"
                   │
                   ▼
                Slug: "john-does-photo-1"
                   │
                   ▼
         MD5 Hash: "abc123ef" (from microtime)
                   │
                   ▼
          Final: "john-does-photo-1-abc123ef.jpg"

Benefits:
✓ Unique (no collisions)
✓ Readable (keeps slug)
✓ Safe (no special chars)
✓ URL-friendly
✓ File-system friendly
```

## Database Schema Pattern

```php
// Mahasiswa model
$table->string('foto_path')->nullable();
// Stores: 'mahasiswa/123/photo-abc123ef.jpg'

// When retrieving:
$url = FileHelper::fileUrl($mahasiswa->foto_path);
// Returns: 'http://app.local/storage/s3/public/mahasiswa/123/photo-abc123ef.jpg'
```

## Error Handling Strategy

```
FileHelper Methods
       │
       ├─ uploadFile()
       │  ├─ Try: putFileAs(...)
       │  ├─ Catch: log error
       │  └─ Return: false or path
       │
       ├─ deleteFile()
       │  ├─ Try: delete(...)
       │  ├─ Catch: log error
       │  └─ Return: true or false
       │
       ├─ fileUrl()
       │  ├─ Try: url(...)
       │  ├─ Catch: log error
       │  └─ Return: null or URL
       │
       └─ fileExistsStorage()
          ├─ Try: exists(...)
          ├─ Catch: log error
          └─ Return: false
```

## Disk Resolution Logic

```
Call: FileHelper::uploadFile('path', $file, 'my-disk')
       │
       ├─ If 'my-disk' provided → Use 'my-disk'
       │
       └─ If null → Use config('filesystems.default')
          │
          ├─ From .env: FILESYSTEM_DISK=s3local
          │
          └─ Or fallback: 's3local'

Result: Storage::disk('s3local') or Storage::disk('my-disk')
```

## Switching Disks (No Code Changes)

### Scenario 1: Local Development
```bash
# .env
FILESYSTEM_DISK=s3local
```
Use: `storage/app/s3/` local directory

### Scenario 2: Production with AWS
```bash
# .env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_BUCKET=...
```
Use: AWS S3 bucket

### Scenario 3: Hybrid (Local + AWS Archive)
```php
// Keep active in local
$path = FileHelper::uploadFile('active', $file, 's3local');

// Archive old to S3
$archivePath = FileHelper::uploadFile('archive', $file, 's3');
```

**All code remains identical!**

## Signed URL Validation

```
User requests private file at time T:
FileHelper::filePrivateUrl($path, 30)
       │
       ├─ Expiration: T + 30 minutes
       ├─ Path: base64_encoded
       └─ URL: /storage/private?disk=s3local&path=...&expires=timestamp

When browser requests URL:
       │
       ├─ Extract expires timestamp
       │
       ├─ Check: expires > now()
       │  └─ If false → 403 Forbidden
       │
       ├─ Validate path format
       │  ├─ No "../" (directory traversal)
       │  └─ Doesn't start with "/"
       │
       └─ If valid → Stream file response
```

## Performance Considerations

### 1. URL Caching
```php
// ❌ DON'T - Regenerate every time
$url = FileHelper::fileUrl($path);

// ✅ DO - Cache the URL
Cache::remember("file_url:{$path}", 60, function () use ($path) {
    return FileHelper::fileUrl($path);
});

// ✅ DO - Store in database
Mahasiswa::update(['foto_url' => FileHelper::fileUrl($path)]);
```

### 2. Partition Large Directories
```php
// ❌ DON'T - All files in one folder
storage/app/s3/public/documents/file1.pdf
storage/app/s3/public/documents/file2.pdf
(1000+ files → slow)

// ✅ DO - Partition by year/month
storage/app/s3/public/documents/2024/04/23/file1.pdf
storage/app/s3/public/documents/2024/04/23/file2.pdf
(distributed load)
```

### 3. Async Upload for Large Files
```php
// Queue upload job
dispatch(new UploadFileJob($file, $path))->onQueue('uploads');

// In Job:
$storedPath = FileHelper::uploadFile($path, $file);
// Update database after completion
```

## Security Architecture

### Public Files
```
storage/app/s3/public/
├─ Direct URL access allowed
├─ No authentication needed
├─ Use for: avatars, logos, profile pics
└─ Served by web server (fast)
```

### Private Files
```
storage/app/s3/private/
├─ Signed URL required
├─ Authentication recommended
├─ Use for: documents, reports, sensitive data
└─ Served by PHP (validated)
```

### Upload Validation
```php
// Browser-side + Server-side
$request->validate([
    'file' => 'required|file|mimes:pdf,jpg,png|max:10240'
]);

// Auto-generated filename prevents:
✓ Executable uploads
✓ Path traversal
✓ Overwrite attacks
```

## Cleanup Strategy

### Soft Delete + Cleanup Job
```php
// When model is deleted:
Mahasiswa::withTrashed()->find($id);

// In scheduled job:
$deleted = Mahasiswa::onlyTrashed()
    ->where('deleted_at', '<', now()->subDays(30))
    ->get();

foreach ($deleted as $item) {
    if ($item->foto_path) {
        FileHelper::deleteFile($item->foto_path);
    }
    $item->forceDelete();
}
```

### Orphaned Files Cleanup
```php
// Find files not referenced in database
$stored = Storage::disk('s3local')->allFiles('mahasiswa');
$referenced = Mahasiswa::whereNotNull('foto_path')
    ->pluck('foto_path')
    ->toArray();

$orphaned = array_diff($stored, $referenced);

foreach ($orphaned as $file) {
    FileHelper::deleteFile($file);
}
```

## Integration Examples

### With Blade Template
```blade
{{-- Public file --}}
<img src="{{ storage_url($mahasiswa->foto_path) }}" alt="Photo">

{{-- Private file --}}
<a href="{{ storage_private_url($dokumen->path, 30) }}">
    Download (expires: 30 min)
</a>

{{-- Conditional --}}
@if (storage_exists($user->cv_path))
    <a href="{{ storage_private_url($user->cv_path) }}">
        CV
    </a>
@endif
```

### With API Response
```php
return response()->json([
    'id' => $mahasiswa->id,
    'nama' => $mahasiswa->user->name,
    'foto_url' => FileHelper::fileUrl($mahasiswa->foto_path),
    'dokumen_url' => FileHelper::filePrivateUrl($mahasiswa->dokumen_path, 60),
]);
```

### With Model Events
```php
class Mahasiswa extends Model {
    protected static function boot() {
        parent::boot();
        
        static::deleting(function ($mahasiswa) {
            if ($mahasiswa->foto_path) {
                FileHelper::deleteFile($mahasiswa->foto_path);
            }
        });
    }
}
```

## Monitoring & Logging

```
All FileHelper operations are logged:
- Location: storage/logs/laravel.log
- Events logged:
  ✓ Upload errors
  ✓ Delete failures
  ✓ URL generation issues
  ✓ File access denials
  
Example log entry:
[2024-04-23] application.ERROR: File upload failed 
{"error":"Disk not found","path":"mahasiswa/123"}
```

## Migration Path (AWS → Local or vice versa)

### From AWS S3 to Local
```bash
# 1. Backup database
php artisan db:backup

# 2. Sync files from S3 to local
aws s3 sync s3://bucket storage/app/s3/public/

# 3. Update .env
FILESYSTEM_DISK=s3local

# 4. Test & verify
# (No code changes needed!)

# 5. Monitor
tail -f storage/logs/laravel.log
```

### From Local to AWS
```bash
# 1. Update .env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...

# 2. Sync local to S3
aws s3 sync storage/app/s3/ s3://bucket/

# 3. Verify all URLs still work
# (Because code uses FileHelper!)

# 4. Done - no refactoring needed!
```

---

**System created: April 23, 2024**
**Framework: Laravel 12**
**Architecture: S3-Like Local Storage**
**Status: Production Ready ✅**
