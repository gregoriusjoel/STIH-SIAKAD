# Sistem Pembayaran Keuangan Mahasiswa

Sistem pembayaran dengan fitur tagihan, cicilan, upload bukti bayar, verifikasi keuangan, dan riwayat pembayaran.

## Features

- ✅ **Tagihan (Invoice)** - Keuangan membuat dan publish tagihan per semester
- ✅ **Pengajuan Cicilan** - Mahasiswa mengajukan cicilan dengan approval keuangan
- ✅ **Upload Bukti Bayar** - Mahasiswa upload bukti transfer per cicilan
- ✅ **Verifikasi Pembayaran** - Keuangan approve/reject bukti bayar
- ✅ **Riwayat Pembayaran** - Mahasiswa melihat pembayaran yang sudah di-approve
- ✅ **Audit Log** - Semua aksi approve/reject tercatat
- ✅ **RBAC** - Role-based access (student/finance) dengan policies

## Tech Stack

- **Backend:** Laravel 10/11, PHP 8.x
- **Frontend:** Blade, TailwindCSS 3.x
- **Database:** MySQL/MariaDB
- **Storage:** Laravel Storage (local/public)
- **Auth:** Laravel Breeze/Jetstream

## Installation

### 1. Clone & Install Dependencies

```bash
git clone <repository-url>
cd KRS-STIH
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=krs_stih
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 3. Run Migrations

```bash
php artisan migrate
```

### 4. Create Storage Link

```bash
php artisan storage:link
```

### 5. Build Assets

```bash
npm run build
# atau untuk development:
npm run dev
```

### 6. Seed Data (Optional)

Create seeder untuk test users:

```php
// database/seeders/UserSeeder.php
User::create([
    'name' => 'Staf Keuangan',
    'email' => 'finance@stih.ac.id',
    'password' => bcrypt('password'),
    'role' => 'finance',
]);

$studentUser = User::create([
    'name' => 'Mahasiswa Test',
    'email' => 'student@stih.ac.id',
    'password' => bcrypt('password'),
    'role' => 'mahasiswa',
]);

Student::create([
    'user_id' => $studentUser->id,
    'npm' => '2024001',
    'nama' => 'Mahasiswa Test',
    'prodi' => 'Ilmu Hukum',
    'angkatan' => '2024',
]);
```

```bash
php artisan db:seed --class=UserSeeder
```

### 7. Load Routes

Add to `routes/web.php`:
```php
require __DIR__.'/payment_routes.php';
```

### 8. Register Policies

Add to `app/Providers/AuthServiceProvider.php`:
```php
protected $policies = [
    Invoice::class => InvoicePolicy::class,
    InstallmentRequest::class => InstallmentRequestPolicy::class,
    PaymentProof::class => PaymentProofPolicy::class,
];
```

### 9. Run Application

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage Flow

### Finance User (Keuangan)

1. **Buat Tagihan**
   - Menu: Finance → Invoices → Create
   - Pilih mahasiswa, semester, total tagihan
   - Save sebagai DRAFT, lalu Publish

2. **Review Pengajuan Cicilan**
   - Menu: Finance → Installment Requests
   - Review detail pengajuan
   - Approve (tentukan jumlah cicilan) atau Reject

3. **Verifikasi Bukti Bayar**
   - Menu: Finance → Payment Proofs
   - Lihat bukti transfer
   - Approve (pembayaran resmi tercatat) atau Reject

### Student User (Mahasiswa)

1. **Lihat Tagihan**
   - Menu: Student → My Invoices
   - Lihat detail tagihan dan status

2. **Ajukan Cicilan**
   - Pilih tagihan → Ajukan Cicilan
   - Input jumlah cicilan & alasan
   - Tunggu approval keuangan

3. **Upload Bukti Bayar**
   - Setelah cicilan disetujui
   - Upload bukti transfer per cicilan (berurutan)
   - Tunggu verifikasi keuangan

4. **Lihat Riwayat Pembayaran**
   - Menu: Student → Payment History
   - Hanya menampilkan pembayaran yang sudah di-approve

## Business Rules

### Cicilan (Installments)

- ✅ Mahasiswa **wajib mengajukan** cicilan, tidak otomatis
- ✅ Cicilan harus **dibayar berurutan** (1 → 2 → 3...)
- ✅ Setiap pembayaran **wajib upload bukti**
- ✅ Pembulatan ke **Rp 1.000**, selisih masuk cicilan terakhir
- ✅ Default: **tidak boleh partial payment**

### Verifikasi

- ✅ Pembayaran resmi **hanya setelah ACC keuangan**
- ✅ `paid_date` = tanggal approve (bukan tanggal transfer)
- ✅ Jika reject: mahasiswa dapat **upload ulang**
- ✅ Cegah **double approve** dengan locking

### Status Flow

**Invoice:**
```
DRAFT → PUBLISHED → IN_INSTALLMENT → LUNAS
```

**Installment Request:**
```
SUBMITTED → APPROVED/REJECTED
```

**Installment:**
```
UNPAID → WAITING_VERIFICATION → PAID
```

**Payment Proof:**
```
UPLOADED → APPROVED/REJECTED
```

## Testing

Run feature tests:

```bash
php artisan test --filter PaymentSystemTest
```

Test coverage:
- ✅ Perhitungan cicilan dengan pembulatan
- ✅ Authorization (student tidak bisa lihat invoice mahasiswa lain)
- ✅ Upload bukti mengubah status installment
- ✅ Approve proof membuat payment & update status
- ✅ Invoice menjadi LUNAS saat semua cicilan PAID
- ✅ Reject proof menyimpan alasan & allow reupload
- ✅ Cegah double approve
- ✅ Larangan bayar cicilan berikutnya sebelum sebelumnya PAID
- ✅ Validasi nominal sesuai tagihan
- ✅ Audit log created
- ✅ File upload validation

## API Endpoints

### Student Routes
```
GET  /student/invoices                           - List tagihan
GET  /student/invoices/{id}                      - Detail tagihan
GET  /student/invoices/{id}/installment-request/create
POST /student/invoices/{id}/installment-request  - Submit cicilan
GET  /student/installments/{id}/payment-proof/create
POST /student/payment-proofs                     - Upload bukti
GET  /student/payments/history                   - Riwayat
```

### Finance Routes
```
GET  /finance/invoices                - List tagihan
POST /finance/invoices                - Create tagihan
POST /finance/invoices/{id}/publish   - Publish
GET  /finance/installment-requests    - List pengajuan
POST /finance/installment-requests/{id}/approve
POST /finance/installment-requests/{id}/reject
GET  /finance/payment-proofs          - List bukti bayar
POST /finance/payment-proofs/{id}/review
```

## Database Schema

**Key Tables:**
- `users` - Auth + role
- `students` - Data mahasiswa
- `invoices` - Tagihan
- `installment_requests` - Pengajuan cicilan
- `installments` - Record cicilan
- `payment_proofs` - Bukti bayar (upload)
- `payments` - Pembayaran resmi (setelah approve)
- `audit_logs` - Audit trail

See migrations in `database/migrations/` for full schema.

## Troubleshooting

### Payment tidak muncul di riwayat
- Pastikan bukti bayar sudah di-APPROVE oleh keuangan
- Riwayat hanya menampilkan data dari tabel `payments`

### Tidak bisa upload bukti cicilan ke-2
- Cicilan ke-1 harus status PAID terlebih dahulu
- Check `installment.canBePaid()` method

### Invoice tidak berubah LUNAS
- Pastikan semua installments berstatus PAID
- Check `invoice.allInstallmentsPaid()` method

### File upload error
- Max size: 2MB
- Format: JPG, JPEG, PNG, PDF
- Pastikan storage link sudah dibuat: `php artisan storage:link`

## Security

- ✅ RBAC dengan Laravel Policies
- ✅ CSRF Protection (Laravel default)
- ✅ DB Transaction dengan `lockForUpdate()`
- ✅ File upload validation
- ✅ Authorization checks per request
- ✅ Audit logging semua sensitive actions

## License

Proprietary - STIH Adhyaksa

## Support

Untuk pertanyaan teknis, hubungi tim development.
