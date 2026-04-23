# S3-Like Storage Integration Checklist

## Phase 1: System Setup ✅ (DONE)
- [x] Config filesystem disk (s3local)
- [x] Create FileHelper.php with 5 core functions
- [x] Create helpers.php with Blade functions
- [x] Create StorageController for private files
- [x] Add storage routes
- [x] Register autoload in composer.json
- [x] Create storage directories
- [x] Setup symbolic links
- [x] Create documentation

**Current Status:** System ready to use

---

## Phase 2: Integration (TODO - Do This Next)

### Step 1: Update Mahasiswa Model
```php
// app/Models/Mahasiswa.php
protected $fillable = [
    // ... existing
    'foto_path',        // Add this
];

// Add accessor for convenience
public function getFotoUrlAttribute()
{
    return $this->foto_path ? storage_url($this->foto_path) : null;
}
```

**Checklist:**
- [ ] Add `foto_path` to $fillable
- [ ] Add `foto_url` accessor (optional)
- [ ] Run migration if new column: `php artisan make:migration add_foto_path_to_mahasiswas`

### Step 2: Update MahasiswaController
```php
// app/Http/Controllers/Admin/MahasiswaController.php
use App\Helpers\FileHelper;

public function store(Request $request)
{
    // ... validation code
    
    // Upload foto if provided
    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = FileHelper::uploadFile('mahasiswa', $request->file('foto'));
    }
    
    // Create mahasiswa with foto_path
    Mahasiswa::create([
        // ... other fields
        'foto_path' => $fotoPath,
    ]);
}

public function update(Request $request, Mahasiswa $mahasiswa)
{
    // Delete old foto if new one provided
    if ($request->hasFile('foto')) {
        if ($mahasiswa->foto_path) {
            FileHelper::deleteFile($mahasiswa->foto_path);
        }
        $mahasiswa->foto_path = FileHelper::uploadFile('mahasiswa', $request->file('foto'));
    }
    
    $mahasiswa->save();
}
```

**Checklist:**
- [ ] Add FileHelper use statement
- [ ] Update store() method
- [ ] Update update() method
- [ ] Handle delete in destroy() method

### Step 3: Update Blade Templates
```blade
<!-- resources/views/admin/mahasiswa/index.blade.php -->
@if ($mahasiswa->foto_path && storage_exists($mahasiswa->foto_path))
    <img src="{{ storage_url($mahasiswa->foto_path) }}" alt="Photo" class="w-12 h-12 rounded">
@else
    <img src="/images/default-avatar.png" alt="Default" class="w-12 h-12 rounded">
@endif
```

**Checklist:**
- [ ] Update mahasiswa/index.blade.php
- [ ] Update mahasiswa/show.blade.php
- [ ] Update mahasiswa/form.blade.php (if exists)
- [ ] Test display in UI

### Step 4: Database Migration
```php
// database/migrations/[timestamp]_add_foto_path_to_mahasiswas.php
public function up()
{
    Schema::table('mahasiswas', function (Blueprint $table) {
        $table->string('foto_path')->nullable()->after('email_aktif');
    });
}

public function down()
{
    Schema::table('mahasiswas', function (Blueprint $table) {
        $table->dropColumn('foto_path');
    });
}
```

**Checklist:**
- [ ] Create migration: `php artisan make:migration add_foto_path_to_mahasiswas`
- [ ] Update migration file
- [ ] Run migration: `php artisan migrate`
- [ ] Verify in phpMyAdmin: new column exists

### Step 5: Test Upload Flow
```bash
1. Go to admin dashboard
2. Add new mahasiswa with foto
3. Verify file stored in: storage/app/s3/public/mahasiswa/
4. Verify URL displays in mahasiswa list
5. Verify file accessible via URL
6. Edit mahasiswa with new foto
7. Verify old file deleted
8. Test without foto
```

**Checklist:**
- [ ] Create mahasiswa with foto → stored correctly
- [ ] Display foto in list → URL correct
- [ ] Update mahasiswa foto → old deleted
- [ ] Delete mahasiswa → foto deleted (if implemented)

---

## Phase 3: Extended Features (OPTIONAL)

### Multiple File Types
```php
// Support multiple documents
protected $fillable = [
    'foto_path',
    'dokumen_ktp_path',
    'dokumen_ijazah_path',
    'dokumen_transkrip_path',
];

// In controller
$paths = [
    'foto_path' => FileHelper::uploadFile('mahasiswa/' . $id, $request->file('foto')),
    'dokumen_ktp_path' => FileHelper::uploadFile('mahasiswa/' . $id . '/ktp', $request->file('ktp')),
];
```

### Private Documents
```php
// For sensitive files
$path = FileHelper::uploadFile('mahasiswa/' . $id . '/private', $file);

// Generate signed URL (1 hour validity)
$url = FileHelper::filePrivateUrl($path, 60);

// In Blade
<a href="{{ storage_private_url($mahasiswa->transkrip_path, 60) }}">
    Download Transkrip (Expires: 1 hour)
</a>
```

### Cleanup on Delete
```php
// app/Models/Mahasiswa.php
protected static function boot()
{
    parent::boot();
    
    static::deleting(function ($mahasiswa) {
        // Delete all associated files
        if ($mahasiswa->foto_path) {
            FileHelper::deleteFile($mahasiswa->foto_path);
        }
        if ($mahasiswa->dokumen_ktp_path) {
            FileHelper::deleteFile($mahasiswa->dokumen_ktp_path);
        }
    });
}
```

### Batch Upload (Import)
```php
// In ImportService or command
foreach ($data as $item) {
    $fotoPath = null;
    if (isset($item['foto'])) {
        $fotoPath = FileHelper::uploadFile('mahasiswa/import', $item['foto']);
    }
    
    Mahasiswa::create([
        // ... fields
        'foto_path' => $fotoPath,
    ]);
}
```

---

## Phase 4: Other Models (OPTIONAL)

### Dosen Model
```php
// app/Models/Dosen.php
protected $fillable = ['foto_path', 'sertifikat_path', ...];

// Storage paths:
// - Foto: 'dosen/{id}/foto.jpg'
// - Sertifikat: 'dosen/{id}/sertifikat.pdf' (private)
```

### Document Model
```php
// app/Models/Document.php
protected $fillable = ['file_path', 'file_type', ...];

// Storage paths:
// - Public: 'dokumen/public/{type}/{id}/'
// - Private: 'dokumen/private/{user_id}/{id}/'
```

---

## Testing Checklist

### Unit Tests
```php
// tests/Unit/FileHelperTest.php
public function test_upload_file()
{
    $file = UploadedFile::fake()->image('test.jpg');
    $path = FileHelper::uploadFile('test', $file);
    
    $this->assertNotFalse($path);
    $this->assertTrue(FileHelper::fileExistsStorage($path));
}
```

- [ ] Test uploadFile()
- [ ] Test deleteFile()
- [ ] Test fileUrl()
- [ ] Test filePrivateUrl()
- [ ] Test fileExistsStorage()

### Feature Tests
```php
// tests/Feature/MahasiswaFileUploadTest.php
public function test_upload_mahasiswa_foto()
{
    $file = UploadedFile::fake()->image('foto.jpg');
    
    $response = $this->post(route('admin.mahasiswa.store'), [
        'foto' => $file,
        // ... other fields
    ]);
    
    $this->assertDatabaseHas('mahasiswas', [
        'nim' => '...',
    ]);
}
```

- [ ] Test store with foto
- [ ] Test update with foto
- [ ] Test display in list
- [ ] Test private file access

---

## Performance & Monitoring

### Monitor Storage Usage
```bash
# Check disk usage
du -sh storage/app/s3/
du -sh storage/app/s3/public/
du -sh storage/app/s3/private/

# Find large files
find storage/app/s3 -type f -size +10M
```

**Checklist:**
- [ ] Monitor disk space regularly
- [ ] Setup alerts if usage > 80%
- [ ] Implement cleanup job if needed

### View Logs
```bash
# Watch real-time logs
tail -f storage/logs/laravel.log

# Filter storage errors
grep "File upload failed" storage/logs/laravel.log
```

**Checklist:**
- [ ] Check logs after uploads
- [ ] Monitor for errors
- [ ] Verify cleanup operations

---

## Deployment Checklist

### Before Going Live
- [ ] Test on staging server
- [ ] Verify storage directories created
- [ ] Run storage:link command
- [ ] Test file upload/download
- [ ] Verify URLs accessible
- [ ] Check file permissions
- [ ] Monitor disk space
- [ ] Setup backup strategy

### Backup Strategy
```bash
# Regular backups
*/1 * * * * tar -czf storage/backups/s3-$(date +\%Y\%m\%d-\%H\%M).tar.gz storage/app/s3/

# Keep 7 days
find storage/backups -name "s3-*.tar.gz" -mtime +7 -delete
```

### Production Considerations
- [ ] Use AWS S3 for production (FILESYSTEM_DISK=s3)
- [ ] Or use local with regular backups
- [ ] Monitor storage costs
- [ ] Setup CDN if needed (optional)
- [ ] Implement cache headers

---

## Troubleshooting

### File not uploading
```
Check:
1. File size <= max_upload_size in php.ini
2. storage/ directory writable: chmod 755
3. Check logs: storage/logs/laravel.log
4. Verify disk config in filesystems.php
```

### URL returns null
```
Check:
1. File exists: FileHelper::fileExistsStorage($path)
2. Disk configured correctly
3. Symbolic links setup: php artisan storage:link
4. Check public/storage/s3 symlink exists
```

### Private file access denied
```
Check:
1. URL not expired: ?expires=timestamp
2. Path doesn't contain ../
3. File exists in storage
4. Route middleware (if added auth)
```

---

## Next: Implementation Guide

**Start with Phase 2, Step 1-5 to integrate with Mahasiswa model**

Estimated time: 2-3 hours
Difficulty: Medium
Impact: High (cleaner file management)

---

Last updated: April 23, 2024
