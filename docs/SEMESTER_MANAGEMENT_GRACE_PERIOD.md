# Semester Management dengan Grace Period - Dokumentasi Teknis

## 📋 Overview

Sistem ini mengimplementasikan **validasi dan perilaku otomatis untuk pergantian semester** dengan fitur **grace period 14 hari** setelah semester berakhir. Ini memastikan transisi yang smooth antara semester lama ke semester baru.

## 🎯 Acceptance Criteria

✅ **14 hari setelah semester berakhir**, kelas semester lama tidak lagi muncul sebagai kelas aktif  
✅ **Ketika semester baru mulai/aktif**, sistem menampilkan jadwal semester baru  
✅ **Tidak ada duplikasi semester aktif** - hanya satu semester yang aktif sekaligus  
✅ **Kode rapih, modular, mudah dirawat**, dan tidak hardcode tanggal  

---

## 🏗️ Arsitektur & Komponen

### 1. **SemesterService** (`app/Services/SemesterService.php`)

Service utama yang mengelola logika semester dan grace period.

**Konstanta:**
```php
const GRACE_PERIOD_DAYS = 14;  // Grace period 14 hari
const CACHE_DURATION = 300;     // Cache 5 menit
```

**Method Utama:**

| Method | Deskripsi |
|--------|-----------|
| `getActiveSemester()` | Mendapatkan semester yang sedang aktif |
| `getActiveSemesterIds()` | Array ID semester yang harus menampilkan kelas (aktif + grace period) |
| `getSemestersInGracePeriod()` | Semester yang sudah berakhir tapi masih dalam 14 hari |
| `isInGracePeriod($semester)` | Cek apakah semester dalam masa grace period |
| `shouldActivate($semester)` | Cek apakah semester perlu diaktifkan otomatis |
| `shouldDeactivate($semester)` | Cek apakah semester perlu dinonaktifkan (lewat grace period) |
| `activateSemester($semester)` | Aktifkan semester (ensure only one active) |
| `deactivateSemester($semester)` | Nonaktifkan semester setelah grace period |
| `processAutomaticStatusUpdates()` | Jalankan update otomatis (dipanggil scheduler) |

**Caching:**
- `active_semester`: Cache semester aktif (5 menit)
- `active_semester_ids`: Cache array ID semester dengan kelas aktif (5 menit)

---

### 2. **Model Updates**

#### **Semester Model** (`app/Models/Semester.php`)

**New Scopes:**
```php
// Semester yang menampilkan kelas aktif
Semester::showingActiveClasses()->get();
```

**New Methods:**
```php
$semester->isInGracePeriod();              // Bool: dalam grace period?
$semester->shouldShowClasses();             // Bool: kelas harus tampil?
$semester->getDaysUntilGracePeriodEnds();  // Int: hari hingga grace berakhir
```

#### **KelasMataKuliah Model** (`app/Models/KelasMataKuliah.php`)

**New Scopes:**
```php
// Filter kelas berdasarkan semester aktif + grace period
KelasMataKuliah::activeClasses()->get();

// Filter kelas semester tertentu
KelasMataKuliah::forSemester($semesterId)->get();

// Filter hanya semester aktif saat ini (tanpa grace period)
KelasMataKuliah::currentSemester()->get();
```

**New Methods:**
```php
$kelas->isFromActiveSemester();  // Bool: dari semester aktif?
```

---

### 3. **Artisan Commands**

#### **UpdateSemesterStatus** (`app/Console/Commands/UpdateSemesterStatus.php`)

Command untuk update status semester otomatis (dipanggil scheduler).

```bash
# Jalankan update status otomatis
php artisan semester:update-status

# Lihat status semester saat ini tanpa mengubah
php artisan semester:update-status --show-status

# Mode verbose
php artisan semester:update-status --verbose
```

**Fungsi:**
1. Mengaktifkan semester yang tanggal mulainya sudah tiba
2. Menonaktifkan semester yang sudah melewati grace period 14 hari
3. Memastikan hanya 1 semester aktif sekaligus
4. Logging semua perubahan

---

### 4. **Scheduler Configuration** (`routes/console.php`)

```php
// Update semester status setiap hari jam 00:00
Schedule::command('semester:update-status')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/semester-status-update.log'));

// Proses transisi semester jam 00:01
Schedule::command('semester:process-transition')
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/semester-transition.log'));
```

---

### 5. **Updated Controllers**

Controllers yang sudah di-update untuk filter kelas aktif:

#### **KelasMataKuliahController**
```php
$kelasMatKul = KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'semester', 'jadwal'])
    ->activeClasses()  // ← Filter semester aktif + grace period
    ->whereHas('jadwal', function($query) {
        $query->where('status', 'active');
    })
    ->paginate(10);
```

#### **Dosen/JadwalController**
```php
$kelasMataKuliahs = KelasMataKuliah::where('dosen_id', $dosen->id)
    ->activeClasses()  // ← Filter semester aktif + grace period
    ->with(['mataKuliah', 'semester'])
    ->whereNotNull('hari')
    ->get();
```

---

## 📊 Flow Diagram: Pergantian Semester

```
┌─────────────────────────────────────────────────────────────┐
│  Semester Genap 2024/2025                                   │
│  Status: Aktif                                              │
│  End Date: 2026-06-30                                       │
└─────────────────────────────────────────────────────────────┘
                          │
                          │ 2026-06-30: Semester ends
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  GRACE PERIOD (14 hari)                                     │
│  2026-06-30 s/d 2026-07-14                                  │
│                                                             │
│  ✓ Kelas semester lama MASIH TAMPIL                        │
│  ✓ Semester Ganjil 2025/2026 bisa AKTIF bersamaan         │
│  ✓ Dosen & mahasiswa bisa akses kelas lama                │
└─────────────────────────────────────────────────────────────┘
                          │
                          │ 2026-07-14: Grace period ends
                          ▼
┌─────────────────────────────────────────────────────────────┐
│  Semester Genap 2024/2025                                   │
│  Status: Non-Aktif (auto-deactivated)                      │
│  Kelas: TIDAK TAMPIL lagi                                  │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│  Semester Ganjil 2025/2026                                  │
│  Status: Aktif                                              │
│  Kelas: TAMPIL                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 🧪 Testing

### Run Unit Tests
```bash
php artisan test --filter SemesterServiceTest
```

**Test Coverage:**
- ✅ Grace period detection
- ✅ Active semester IDs calculation
- ✅ Activation/deactivation logic
- ✅ Class filtering by semester
- ✅ Automatic status updates

### Run Feature Tests
```bash
php artisan test --filter SemesterTransitionFeatureTest
```

**Test Scenarios:**
- ✅ Classes disappear after 14 days
- ✅ Classes visible during grace period
- ✅ New semester shows correct schedules
- ✅ No duplicate active semesters

---

## 🚀 Deployment Checklist

### 1. **Migrasi Database**
```bash
php artisan migrate
```

Migrations yang diperlukan sudah ada:
- ✅ `2026_01_15_030153_create_semesters_table.php`
- ✅ `2026_02_13_000001_add_semester_transition_fields.php`

### 2. **Setup Scheduler**

Di **cron** server, pastikan Laravel scheduler berjalan:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. **Clear Cache**
```bash
php artisan cache:clear
```

### 4. **Test Command Manual**
```bash
# Lihat status semester
php artisan semester:update-status --show-status

# Jalankan update (dry-run)
php artisan semester:update-status
```

### 5. **Monitor Logs**
```bash
tail -f storage/logs/semester-status-update.log
tail -f storage/logs/semester-transition.log
```

---

## 📖 Usage Examples

### 1. Query Kelas Aktif (dengan Grace Period)
```php
use App\Models\KelasMataKuliah;

// Hanya kelas dari semester aktif + grace period
$activeClasses = KelasMataKuliah::activeClasses()
    ->with(['mataKuliah', 'dosen', 'semester'])
    ->get();
```

### 2. Query Kelas Semester Aktif Saja (tanpa Grace)
```php
// Hanya kelas dari semester yang sedang aktif
$currentClasses = KelasMataKuliah::currentSemester()
    ->with(['mataKuliah', 'dosen'])
    ->get();
```

### 3. Cek Apakah Kelas Masih Aktif
```php
$kelas = KelasMataKuliah::find($id);

if ($kelas->isFromActiveSemester()) {
    // Kelas masih aktif/visible
}
```

### 4. Get Semester Aktif
```php
use App\Services\SemesterService;

$semesterService = app(SemesterService::class);

// Get active semester
$activeSemester = $semesterService->getActiveSemester();

// Get all semester IDs that show classes
$activeSemesterIds = $semesterService->getActiveSemesterIds();

// Get detailed status
$status = $semesterService->getSemesterStatus($semesterSemester);
```

### 5. Manual Activation/Deactivation
```php
use App\Services\SemesterService;
use App\Models\Semester;

$semesterService = app(SemesterService::class);
$semester = Semester::find($id);

// Activate (akan deactivate semester lain)
$semesterService->activateSemester($semester);

// Deactivate
$semesterService->deactivateSemester($semester);
```

---

## ⚠️ Important Notes

### Grace Period Behavior
- **Semester lama tetap visible selama 14 hari** setelah `tanggal_selesai`
- Setelah 14 hari, kelas semester lama **otomatis hilang** dari daftar kelas aktif
- **Tidak perlu aksi manual** - semua otomatis via scheduler

### Semester Aktif
- **Hanya 1 semester yang berstatus `is_active = true`** di satu waktu
- Semester dalam grace period memiliki `is_active = false` tapi kelasnya masih tampil
- Filter kelas menggunakan `getActiveSemesterIds()` yang include grace period

### Caching
- Semester aktif dan IDs di-cache selama 5 menit
- Cache otomatis di-clear saat ada perubahan status semester
- Untuk clear manual: `app(SemesterService::class)->clearCache()`

### Database Consistency
- Field `status` (enum) dan `is_active` (boolean) harus sinkron
- `activateSemester()` dan `deactivateSemester()` update keduanya
- Scheduler berjalan daily untuk maintain consistency

---

## 🐛 Troubleshooting

### Kelas Lama Tidak Hilang Setelah 14 Hari
1. Cek apakah scheduler berjalan:
   ```bash
   php artisan schedule:list
   ```
2. Manual trigger update:
   ```bash
   php artisan semester:update-status
   ```
3. Cek tanggal `tanggal_selesai` semester:
   ```bash
   php artisan tinker
   >>> App\Models\Semester::all(['id', 'nama_semester', 'tanggal_selesai', 'is_active']);
   ```

### Kelas Tidak Muncul Padahal Semester Aktif
1. Clear cache:
   ```bash
   php artisan cache:clear
   ```
2. Cek semester status:
   ```bash
   php artisan semester:update-status --show-status
   ```
3. Cek apakah `semester_id` di `kelas_mata_kuliahs` benar

### Multiple Semester Aktif
1. Manual fix:
   ```bash
   php artisan tinker
   >>> app(App\Services\SemesterService::class)->activateSemester(App\Models\Semester::find($id));
   ```

---

## 📞 Support

Untuk pertanyaan atau issue terkait fitur ini:
1. Cek test files untuk contoh usage
2. Review logs di `storage/logs/semester-*.log`
3. Jalankan command dengan `--verbose` flag untuk detail

---

**Last Updated:** February 2026  
**Version:** 1.0.0  
**Maintainer:** Development Team
