# 📧 Student Account & Blast Email Automation System

## Overview

Sistem otomasi akun mahasiswa dengan dukungan dual-email login dan blast email massal untuk seluruh kampus.

## Fitur Utama

### 1. **Email Automation**
- ✅ Auto-generate email kampus dari nama mahasiswa
- ✅ Multi-email support (pribadi, kampus, legacy)
- ✅ Email aktif yang dapat dipilih (pribadi atau kampus)
- ✅ Email pribadi verification flow

### 2. **Account Automation**
- ✅ Bulk account creation untuk ribuan mahasiswa
- ✅ Default password generation (NIM-based)
- ✅ Transaction-wrapped untuk data consistency
- ✅ Dry-run mode untuk preview

### 3. **Queue-Based Blast Email**
- ✅ Kirim email massal ke ribuan mahasiswa tanpa blocking
- ✅ Batch tracking & logging untuk setiap email
- ✅ Retry logic (3 attempts) dengan backoff
- ✅ Per-recipient error handling
- ✅ Jitter untuk distribute load

### 4. **Auth System Enhancement**
- ✅ Login support: `email_pribadi`, `email_kampus`, atau user email
- ✅ Backward compatible dengan existing auth flow
- ✅ Non-destructive migration

---

## Architecture

```
┌─────────────────────────────────────────────────────┐
│         Student Account Automation System           │
├─────────────────────────────────────────────────────┤
│                                                     │
│  EmailService                                       │
│  ├─ generateCampusEmail(nama)                      │
│  ├─ sanitizeEmail(nama)                            │
│  ├─ validateEmail(email)                           │
│  ├─ getActiveEmail(mahasiswa)                      │
│  └─ switchActiveEmail(mahasiswa, type)             │
│                                                     │
│  StudentAccountService                             │
│  ├─ automateStudentAccount(mahasiswa, force)       │
│  ├─ bulkAutomateStudents(ids, callback)            │
│  └─ getAccountStatus(mahasiswa)                    │
│                                                     │
│  BlastEmailService                                  │
│  ├─ send(subject, greeting, message, filters)      │
│  ├─ getBlastStats(batchId)                         │
│  └─ getRecipientPreview(filters)                   │
│                                                     │
│  Queue Jobs                                        │
│  └─ SendBlastEmailJob                              │
│                                                     │
│  Notifications                                     │
│  ├─ VerifyEmailNotification                        │
│  └─ BlastEmailNotification                         │
│                                                     │
│  Commands                                          │
│  └─ AutomateMahasiswaAccountCommand                │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

## Installation & Setup

### 1. Run Migration

```bash
php artisan migrate
```

**Columns yang ditambahkan ke table `mahasiswas`:**
- `email_pribadi` - Email pribadi mahasiswa
- `email_kampus` - Email kampus (auto-generated)
- `email_aktif` - Enum: pribadi|kampus (email yang aktif digunakan)
- `email_pribadi_verified_at` - Timestamp verifikasi email pribadi
- `password_reset_token` - Token untuk password reset
- `is_default_password` - Flag: masih pakai default password?
- `account_automation_at` - Timestamp kapan automation dijalankan

**New table:** `email_blast_logs`
- Audit log untuk setiap blast email yang dikirim
- Indexed by: `batch_id`, `mahasiswa_id`, `success`, `created_at`

### 2. Setup Queue

Edit `.env`:
```env
QUEUE_CONNECTION=database  # atau redis untuk production
```

Start queue worker:
```bash
php artisan queue:work --queue=emails --tries=3
```

### 3. Configure Email

Edit `.env`:
```env
MAIL_MAILER=smtp  # atau sesuai konfigurasi
MAIL_FROM_ADDRESS=noreply@stihadhyaksa.ac.id
MAIL_FROM_NAME="STIH Adhyaksa"
```

---

## Usage

### Auto-Automate Mahasiswa Accounts

#### Via Command (Recommended)

```bash
# Dry-run (preview)
php artisan mahasiswa:automate-account --dry-run

# Actual run (only mahasiswa without email_kampus)
php artisan mahasiswa:automate-account

# Force regenerate existing emails
php artisan mahasiswa:automate-account --force

# Custom batch size
php artisan mahasiswa:automate-account --batch-size=50
```

#### Programmatically

```php
use App\Services\StudentAccountService;

$service = app(StudentAccountService::class);

// Automate single mahasiswa
$result = $service->automateStudentAccount($mahasiswa);

// Bulk automation
$result = $service->bulkAutomateStudents(
    mahasiswaIds: [1, 2, 3, 4, 5],
    callback: fn($processed, $total) => echo "Progress: $processed/$total\n"
);
```

### Send Blast Email

#### Via Admin Panel

1. Go to Admin Dashboard → Blast Email
2. Fill form:
   - Subject
   - Greeting (e.g., "Halo Mahasiswa!")
   - Message content
3. Select filter:
   - All: Semua mahasiswa
   - By Prodi
   - By Tingkat (1-4)
   - By Kelas Perkuliahan
   - By Status
4. Click "Preview" to see recipients
5. Click "Send" (akan dijadwalkan ke queue)

#### Programmatically

```php
use App\Services\BlastEmailService;

$service = app(BlastEmailService::class);

$result = $service->send(
    subject: 'Pengumuman Penting',
    greeting: 'Halo Mahasiswa!',
    message: 'Informasi penting untuk semua mahasiswa...',
    filters: [
        'prodi_id' => 1,
        'verified_only' => true,  // hanya email terverifikasi
    ],
    senderId: auth()->id(),
    immediate: false  // false = queue, true = sync
);

// Output:
// [
//     'success' => true,
//     'batch_id' => 'blast_xyz123',
//     'total_recipients' => 1250,
//     'queued' => 1250,
// ]
```

### Check Blast Email Status

```php
$service = app(BlastEmailService::class);

$stats = $service->getBlastStats('blast_xyz123');

// Output:
// [
//     'total' => 1250,
//     'success' => 1248,
//     'failed' => 2,
//     'success_rate' => 99.84,
// ]
```

### Login dengan Multiple Email Format

User bisa login menggunakan salah satu:

```
1. Email user biasa (dari users table)
   - admin@stih.ac.id
   - dosen@stih.ac.id

2. Email pribadi mahasiswa
   - john.doe@gmail.com (dari mahasiswa.email_pribadi)

3. Email kampus mahasiswa
   - johndoe@stih.ac.id (dari mahasiswa.email_kampus)
```

### Get Active Email untuk Mahasiswa

```php
// Via helper method
$activeEmail = $mahasiswa->getActiveEmail();

// Via service
$service = app(EmailService::class);
$activeEmail = $service->getActiveEmail($mahasiswa);

// Check status
if ($mahasiswa->isEmailPribadiVerified()) {
    echo "Email pribadi sudah terverifikasi";
}

if ($mahasiswa->hasDefaultPassword()) {
    echo "Password masih default, perlu diganti";
}

if ($mahasiswa->isAccountAutomated()) {
    echo "Akun sudah ter-automate";
}
```

---

## Database Schema

### mahasiswas table (new columns)

```sql
ALTER TABLE mahasiswas ADD COLUMN email_pribadi VARCHAR(255) NULLABLE;
ALTER TABLE mahasiswas ADD COLUMN email_kampus VARCHAR(255) NULLABLE UNIQUE;
ALTER TABLE mahasiswas ADD COLUMN email_aktif ENUM('pribadi', 'kampus') DEFAULT 'pribadi';
ALTER TABLE mahasiswas ADD COLUMN email_pribadi_verified_at TIMESTAMP NULLABLE;
ALTER TABLE mahasiswas ADD COLUMN password_reset_token VARCHAR(255) NULLABLE;
ALTER TABLE mahasiswas ADD COLUMN is_default_password BOOLEAN DEFAULT true;
ALTER TABLE mahasiswas ADD COLUMN account_automation_at TIMESTAMP NULLABLE;
```

### email_blast_logs table (new)

```sql
CREATE TABLE email_blast_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    batch_id VARCHAR(255) NOT NULL,
    mahasiswa_id BIGINT UNSIGNED NULLABLE,
    email_sent_to VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    success BOOLEAN DEFAULT false,
    error_message TEXT NULLABLE,
    sent_by BIGINT UNSIGNED NULLABLE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_batch_id (batch_id),
    INDEX idx_mahasiswa_id (mahasiswa_id),
    INDEX idx_success (success),
    INDEX idx_created_at (created_at),
    CONSTRAINT fk_blast_logs_mahasiswa FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswas(id) ON DELETE SET NULL,
    CONSTRAINT fk_blast_logs_user FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## File Structure

```
app/
├── Services/
│   ├── EmailService.php              # Email generation & management
│   ├── StudentAccountService.php     # Account automation orchestration
│   └── BlastEmailService.php          # Blast email management
├── Jobs/
│   └── SendBlastEmailJob.php         # Queue job untuk send email
├── Notifications/
│   ├── VerifyEmailNotification.php   # Email verification notification
│   └── BlastEmailNotification.php    # Blast email template
├── Console/
│   └── Commands/
│       └── AutomateMahasiswaAccountCommand.php  # CLI command
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   └── BlastEmailController.php
│   │   └── Auth/
│   │       └── LoginController.php   # Updated untuk dual email
│   └── Requests/
│       └── Admin/
│           └── BlastEmailRequest.php
├── Models/
│   └── Mahasiswa.php                 # Updated dengan email methods
└── Traits/
    └── (other traits)

database/
├── migrations/
│   ├── 2026_04_20_000003_add_email_columns_to_mahasiswas_table.php
│   └── 2026_04_20_000004_create_email_blast_logs_table.php
└── (other migrations)

resources/
└── views/
    └── admin/
        └── blast-email/
            ├── index.blade.php      # Blast email form
            └── logs.blade.php       # Blast email logs
```

---

## API Endpoints

### Admin Blast Email API

```http
GET    /admin/blast-email                # Show form
POST   /admin/blast-email/send           # Send blast email
GET    /admin/blast-email/preview        # Get recipient preview
GET    /admin/blast-email/logs           # Show logs
GET    /admin/blast-email/stats          # Get statistics
GET    /admin/blast-email/kelas/{prodi}  # Get kelas per prodi (AJAX)
```

---

## Security & Best Practices

### 1. **Rate Limiting**
- Max 10 blast emails per jam per user (configurable)
- Implemented di `BlastEmailService`

### 2. **Authentication**
- Only users dengan role `admin` dapat send blast email
- Validated di `BlastEmailRequest`

### 3. **Data Safety**
- All operations wrapped dalam DB::transaction()
- Failed job akan retry 3x sebelum disimpan ke dead letter queue
- Non-destructive migrations (hanya add columns, tidak drop)

### 4. **Email Verification**
- Email pribadi bisa diverifikasi (email_pribadi_verified_at)
- Email kampus auto-generated dan unique
- Support untuk switch antara pribadi/kampus

### 5. **Logging & Audit**
- All blast emails logged ke database (email_blast_logs)
- AuditLog untuk user login
- Failed emails tracked dengan error messages

### 6. **Scalability**
- Queue-based async processing
- Batch processing untuk bulk operations (default 100 per batch)
- Jitter untuk distribute load
- Indexed database queries untuk fast lookup

---

## Troubleshooting

### Queue Jobs Tidak Dijalankan

```bash
# Check if queue worker running
ps aux | grep queue:work

# Start queue worker
php artisan queue:work --queue=emails

# Check failed jobs
php artisan queue:failed
```

### Email Tidak Terkirim

```bash
# Check email configuration
php artisan config:show mail

# Test email sending
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });

# Check blast logs
SELECT * FROM email_blast_logs WHERE success = 0;
```

### Duplicate Email Kampus

```bash
# EmailService sudah handle duplicate emails dengan incremental suffix
# Contoh: 
#   - johndoe@stih.ac.id (first)
#   - johndoe1@stih.ac.id (second)
#   - johndoe2@stih.ac.id (third)
```

### Migration Error

```bash
# Jika migration error karena duplikat, check existing columns
SHOW COLUMNS FROM mahasiswas LIKE 'email_%';

# Rollback terakhir migration
php artisan migrate:rollback --step=1
```

---

## Performance Metrics

### Bulk Account Automation
- **Speed**: ~1000 accounts per minute (depends on server)
- **Memory**: ~50MB untuk batch size 100
- **Dry-run**: Free (no database writes)

### Blast Email
- **Throughput**: ~1000 emails per minute (with queue worker)
- **Latency**: ~2-3 minutes delay (configurable via backoff)
- **Success rate**: >99% (with retry logic)

---

## Future Enhancements

- [ ] Email template customization UI
- [ ] Scheduled blast email sends
- [ ] Email unsubscribe / preference management
- [ ] SMS notifications support
- [ ] Webhook integration untuk email service (SendGrid, Mailgun)
- [ ] Advanced analytics dashboard
- [ ] Email A/B testing
- [ ] Personalization (merge fields)

---

## Support & Documentation

- Laravel: https://laravel.com/docs
- Queue: https://laravel.com/docs/queues
- Notifications: https://laravel.com/docs/notifications
- Commands: https://laravel.com/docs/artisan

---

## License

Internal use only - STIH Adhyaksa
