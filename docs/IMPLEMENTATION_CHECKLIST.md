# 📋 IMPLEMENTATION CHECKLIST
## Sistem Pembayaran Keuangan Mahasiswa

---

## ✅ PRE-IMPLEMENTATION

### Environment Setup
- [ ] PHP 8.x installed
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] MySQL/MariaDB installed
- [ ] Laravel 10/11 project ready

---

## ✅ PHASE 1: DATABASE SETUP

### 1.1 Run Migrations
```bash
php artisan migrate
```

**Verify:**
- [ ] Table `users` has `role` column
- [ ] Table `students` created
- [ ] Table `invoices` created
- [ ] Table `installment_requests` created
- [ ] Table `installments` created
- [ ] Table `payment_proofs` created
- [ ] Table `payments` created
- [ ] Table `audit_logs` created

### 1.2 Configure Storage
```bash
php artisan storage:link
```

**Verify:**
- [ ] `public/storage` symlink created
- [ ] Can upload files to `storage/app/public/payment-proofs/`

---

## ✅ PHASE 2: REGISTER COMPONENTS

### 2.1 Register Policies
**File:** `app/Providers/AuthServiceProvider.php`

```php
protected $policies = [
    \App\Models\Invoice::class => \App\Policies\InvoicePolicy::class,
    \App\Models\InstallmentRequest::class => \App\Policies\InstallmentRequestPolicy::class,
    \App\Models\PaymentProof::class => \App\Policies\PaymentProofPolicy::class,
];
```

**Verify:**
- [ ] Policies registered
- [ ] `php artisan policy:list` shows policies

### 2.2 Load Payment Routes
**File:** `routes/web.php`

```php
// Add at the end
require __DIR__.'/payment_routes.php';
```

**Verify:**
- [ ] Routes loaded
- [ ] `php artisan route:list` shows payment routes

### 2.3 Configure Filesystem
**File:** `.env`

```env
FILESYSTEM_DISK=public
```

**Verify:**
- [ ] Storage disk configured

---

## ✅ PHASE 3: SEED TEST DATA

### 3.1 Create Test Users

**Create:** `database/seeders/PaymentSystemSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Finance User
        User::create([
            'name' => 'Staf Keuangan',
            'email' => 'finance@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'email_verified_at' => now(),
        ]);

        // Student User 1
        $student1 = User::create([
            'name' => 'Ahmad Mahasiswa',
            'email' => 'student1@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $student1->id,
            'npm' => '2024001',
            'nama' => 'Ahmad Mahasiswa',
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2024',
        ]);

        // Student User 2
        $student2 = User::create([
            'name' => 'Siti Mahasiswi',
            'email' => 'student2@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $student2->id,
            'npm' => '2024002',
            'nama' => 'Siti Mahasiswi',
            'prodi' => 'Hukum Bisnis',
            'angkatan' => '2024',
        ]);
    }
}
```

```bash
php artisan db:seed --class=PaymentSystemSeeder
```

**Verify:**
- [ ] 1 finance user created
- [ ] 2 student users created with profiles
- [ ] Can login with test accounts

---

## ✅ PHASE 4: FRONTEND ASSETS

### 4.1 Build Assets
```bash
npm install
npm run build
```

**Verify:**
- [ ] `public/build/` directory created
- [ ] CSS & JS compiled

### 4.2 Layout File
Ensure you have base layout: `resources/views/layouts/app.blade.php`

**Must include:**
- [ ] TailwindCSS CDN or compiled CSS
- [ ] @yield('content')
- [ ] Flash message handling
- [ ] Navigation menu

---

## ✅ PHASE 5: TESTING

### 5.1 Run Feature Tests
```bash
php artisan test --filter PaymentSystemTest
```

**Expected:** 15 tests pass ✅

### 5.2 Manual Testing Checklist

#### As Finance User (finance@stih.ac.id / password)

- [ ] **Create Invoice**
  - Navigate to `/finance/invoices/create`
  - Fill form dan save
  - Status = DRAFT

- [ ] **Publish Invoice**
  - Klik "Publish" di invoice detail
  - Status berubah = PUBLISHED

- [ ] **Review Installment Request**
  - Setelah mahasiswa ajukan cicilan
  - Navigate to `/finance/installment-requests`
  - Review dan approve
  - Verify installments created

- [ ] **Verify Payment Proof**
  - Setelah mahasiswa upload bukti
  - Navigate to `/finance/payment-proofs`
  - View detail dan approve
  - Verify payment created

#### As Student User (student1@stih.ac.id / password)

- [ ] **View Invoices**
  - Navigate to `/student/invoices`
  - See only PUBLISHED invoices
  - See only own invoices

- [ ] **Request Installment**
  - Pilih invoice → "Ajukan Cicilan"
  - Fill form (terms + alasan)
  - Submit → status SUBMITTED

- [ ] **Upload Payment Proof**
  - Setelah cicilan approved
  - Klik "Upload Bukti" pada cicilan ke-1
  - Upload file (JPG/PNG/PDF max 2MB)
  - Submit → status WAITING_VERIFICATION

- [ ] **View Payment History**
  - Navigate to `/student/payments/history`
  - See only approved payments
  - Verify correct amounts

---

## ✅ PHASE 6: EDGE CASES TESTING

### 6.1 Authorization Tests
- [ ] Student cannot access other student's invoice
- [ ] Student cannot access finance routes
- [ ] Finance can access all invoices

### 6.2 Business Logic Tests
- [ ] Cannot pay cicilan ke-2 sebelum ke-1 PAID
- [ ] Cannot upload bukti saat status WAITING_VERIFICATION
- [ ] Cannot double approve same proof
- [ ] Installment calculation correct (rounding)
- [ ] Invoice become LUNAS when all paid

### 6.3 Validation Tests
- [ ] File upload validation (type, size)
- [ ] Amount validation (must match if no partial)
- [ ] Transfer date cannot be future
- [ ] Required fields validated

---

## ✅ PHASE 7: PRODUCTION PREPARATION

### 7.1 Environment Configuration
**File:** `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

FILESYSTEM_DISK=s3  # or public for local
```

### 7.2 Security Checklist
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secured
- [ ] `.env` not in git
- [ ] HTTPS enabled
- [ ] CORS configured if needed
- [ ] Rate limiting enabled

### 7.3 Performance Optimization
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Enable query caching if needed
- [ ] Setup queue worker for heavy tasks
- [ ] Configure proper logging

### 7.4 Backup Strategy
- [ ] Database backup scheduled
- [ ] File storage backup (especially payment-proofs/)
- [ ] Audit logs backup strategy

---

## ✅ PHASE 8: DEPLOYMENT

### 8.1 Server Requirements
- [ ] PHP 8.x with required extensions
- [ ] MySQL/MariaDB 5.7+
- [ ] Nginx/Apache configured
- [ ] SSL certificate installed
- [ ] Cron job for scheduler (if needed)

### 8.2 Deploy Steps
```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Run migrations
php artisan migrate --force

# 4. Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 6. Create storage link
php artisan storage:link

# 7. Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

### 8.3 Post-Deployment Verification
- [ ] Application accessible
- [ ] Database connected
- [ ] File upload works
- [ ] Login works
- [ ] All routes accessible
- [ ] No errors in logs

---

## ✅ PHASE 9: MONITORING

### 9.1 Setup Monitoring
- [ ] Error tracking (Sentry, Bugsnag)
- [ ] Application monitoring (New Relic, DataDog)
- [ ] Server monitoring (CPU, Memory, Disk)
- [ ] Database monitoring (query performance)

### 9.2 Logging
- [ ] Application logs configured
- [ ] Error logs reviewed daily
- [ ] Audit logs retained properly
- [ ] Payment transactions logged

---

## ✅ PHASE 10: DOCUMENTATION

### 10.1 User Documentation
- [ ] User manual for students
- [ ] User manual for finance staff
- [ ] FAQ document
- [ ] Video tutorials (optional)

### 10.2 Technical Documentation
- [ ] API documentation (if any)
- [ ] Database schema diagram
- [ ] System architecture diagram
- [ ] Deployment guide

### 10.3 Training
- [ ] Finance staff trained
- [ ] Support team briefed
- [ ] Student orientation session

---

## 🎯 SUCCESS CRITERIA

### Functional
- [ ] Finance dapat membuat & publish tagihan
- [ ] Mahasiswa dapat ajukan cicilan
- [ ] Finance dapat approve/reject cicilan
- [ ] Installments ter-generate otomatis dengan amount benar
- [ ] Mahasiswa dapat upload bukti bayar
- [ ] Finance dapat verifikasi bukti bayar
- [ ] Payment tercatat setelah approve
- [ ] Riwayat pembayaran akurat
- [ ] Audit log lengkap

### Non-Functional
- [ ] Response time < 2 detik
- [ ] Dapat handle 100+ concurrent users
- [ ] 99.9% uptime
- [ ] Data secure & encrypted
- [ ] Backup recovery tested

---

## 🚀 GO-LIVE CHECKLIST

**Final checks before production:**

- [ ] All tests passing
- [ ] Manual testing completed
- [ ] Security audit done
- [ ] Performance tested
- [ ] Backup strategy verified
- [ ] Rollback plan ready
- [ ] Support team ready
- [ ] Users notified
- [ ] Monitoring active

**READY TO LAUNCH!** 🎉

---

## 📞 TROUBLESHOOTING QUICK REFERENCE

### Common Issues

**Storage link not working?**
```bash
php artisan storage:link
# Check: ls -la public/storage
```

**Policies not working?**
```bash
php artisan cache:clear
php artisan config:clear
```

**Routes not found?**
```bash
php artisan route:clear
php artisan route:cache
```

**Payment not showing in history?**
- Check: status proof = 'APPROVED'
- Check: payment record exists
- Check: query includes proper relations

**Cannot upload file?**
- Check: storage permission (775)
- Check: max upload size in php.ini
- Check: .env FILESYSTEM_DISK

---

**Last Updated:** 18 Februari 2026  
**Version:** 1.0.0  
**Status:** ✅ Ready for Implementation
