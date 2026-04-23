# Storage Testing Configuration

## Setup Selesai ✅

Folder testing sudah dikonfigurasi untuk development & testing:
```
External Storage: /Users/naradata/storage-stih
├── public/     (symlink: public/storage-stih)
└── private/
```

## Configuration

### .env (Updated)
```bash
FILESYSTEM_DISK=s3local
S3LOCAL_ROOT=/Users/naradata/storage-stih
S3LOCAL_URL_PATH=/storage-stih
```

### config/filesystems.php (Updated)
```php
's3local' => [
    'driver' => 'local',
    'root' => env('S3LOCAL_ROOT', storage_path('app/s3')),
    'url' => rtrim(env('APP_URL'), '/').env('S3LOCAL_URL_PATH', '/storage/s3'),
    'visibility' => 'public',
    'throw' => false,
    'report' => false,
],
```

## File Paths

### Upload Public Files
```
/Users/naradata/storage-stih/public/{resource_type}/{id}/{filename}
```
Example: `/Users/naradata/storage-stih/public/mahasiswa/123/photo-abc123ef.jpg`

### Upload Private Files
```
/Users/naradata/storage-stih/private/{resource_type}/{id}/{filename}
```
Example: `/Users/naradata/storage-stih/private/dokumen/transkrip.pdf`

## URL Access

### Public Files
```
http://192.168.1.7:8000/storage-stih/mahasiswa/123/photo-abc123ef.jpg
```
Direct access via symlink: `public/storage-stih → /Users/naradata/storage-stih/public`

### Private Files
```
http://192.168.1.7:8000/storage/private?disk=s3local&path=...&expires=timestamp
```
Via signed route with validation

## Testing Upload

### Option 1: Via UI
1. Go to admin dashboard
2. Add/Edit Mahasiswa with foto
3. File should store in `/Users/naradata/storage-stih/public/`
4. URL should be accessible

### Option 2: Via Artisan Tinker
```php
php artisan tinker
>>> use App\Helpers\FileHelper;
>>> $file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
>>> $path = FileHelper::uploadFile('test', $file);
>>> dd($path);
>>> // Check file exists
>>> ls -la /Users/naradata/storage-stih/
```

### Option 3: Via Blade/Controller
```php
// In controller
$path = FileHelper::uploadFile('mahasiswa/123', $request->file('foto'));
$url = FileHelper::fileUrl($path);
dd($url);
```

## Verification Checklist

- [ ] Folder `/Users/naradata/storage-stih/` exists
- [ ] Subfolders: `public/` dan `private/` created
- [ ] Symlink: `public/storage-stih` points to external folder
- [ ] .env: `FILESYSTEM_DISK=s3local` set
- [ ] .env: `S3LOCAL_ROOT=/Users/naradata/storage-stih` set
- [ ] Config: filesystems.php uses env variables
- [ ] Cache cleared: `php artisan config:clear`
- [ ] Test upload: File appears in `/Users/naradata/storage-stih/public/`
- [ ] Test URL: Browser can access file via `/storage-stih/{path}`

## Switch Back to Production

### Back to Local Storage (original)
```bash
# .env
FILESYSTEM_DISK=s3local
S3LOCAL_ROOT=
S3LOCAL_URL_PATH=

# Will use default: storage_path('app/s3') & '/storage/s3'
```

### To AWS S3
```bash
# .env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
```

## Troubleshooting

### File not saving
Check:
```bash
ls -la /Users/naradata/storage-stih/
# Should show public/ and private/ folders

# Check if writable
touch /Users/naradata/storage-stih/public/test.txt && rm /Users/naradata/storage-stih/public/test.txt
```

### URL shows 404
```bash
# Verify symlink
ls -la public/storage-stih
# Should point to /Users/naradata/storage-stih/public

# Check file in folder
ls -la /Users/naradata/storage-stih/public/
```

### FileHelper returns null
Check logs:
```bash
tail -f storage/logs/laravel.log
# Look for FileHelper errors
```

## Development Tips

### Easy Cleanup
```bash
# Delete all test files
rm -rf /Users/naradata/storage-stih/public/*
rm -rf /Users/naradata/storage-stih/private/*

# Or delete specific type
rm -rf /Users/naradata/storage-stih/public/mahasiswa/
```

### Monitor Uploads
```bash
# Watch folder in real-time
ls -laR /Users/naradata/storage-stih/
# Or in a terminal tab:
watch -n 1 'ls -laR /Users/naradata/storage-stih/'
```

### Check File Size
```bash
du -sh /Users/naradata/storage-stih/
du -sh /Users/naradata/storage-stih/public/
du -sh /Users/naradata/storage-stih/private/
```

## Notes

- ✅ External folder for easier testing & cleanup
- ✅ Symlink allows direct web access
- ✅ No code changes needed to FileHelper
- ✅ Easy to cleanup & reset for testing
- ✅ Can switch anytime via .env
- ✅ Original storage/app/s3 not affected

---

**Created: April 23, 2024**
**For: Testing & Development**
**Status: ✅ Ready**
