# Dokumentasi Modul Magang Mahasiswa (SIAKAD STIH)

**Versi:** 1.0  
**Tanggal:** Maret 2026  
**Dibuat untuk:** Admin, Mahasiswa, Dosen Pembimbing

---

## Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Alur Status (State Machine)](#2-alur-status-state-machine)
3. [Panduan Mahasiswa](#3-panduan-mahasiswa)
4. [Panduan Admin / Akademik](#4-panduan-admin--akademik)
5. [Panduan Dosen Pembimbing](#5-panduan-dosen-pembimbing)
6. [Integrasi Sistem](#6-integrasi-sistem)
7. [Struktur Database](#7-struktur-database)
8. [Setup Template Surat DOCX](#8-setup-template-surat-docx)
9. [Referensi File](#9-referensi-file)

---

## 1. Gambaran Umum

Modul Magang memungkinkan mahasiswa mengajukan program magang, mendapatkan surat resmi dari kampus, dibimbing oleh dosen, dan mendapatkan konversi nilai SKS (maks 16 SKS) yang otomatis masuk ke KRS.

### Fitur Utama

| Fitur | Keterangan |
|-------|-----------|
| Pengajuan Magang | Mahasiswa isi form instansi, periode, posisi |
| Generate Surat | Surat Permohonan & Penerimaan otomatis dari template DOCX |
| Dosen Pembimbing | Admin assign dosen; dosen bisa lihat & komentari logbook |
| KRS Konversi | Auto-inject MK konversi ke KRS saat magang dimulai |
| Kehadiran Otomatis | MK konversi otomatis dianggap HADIR (100%) |
| Logbook Harian | Mahasiswa & dosen bisa tambah entri bimbingan |
| Input Nilai | Admin input 1 nilai, otomatis menyebar ke semua MK konversi |

---

## 2. Alur Status (State Machine)

```
DRAFT
  │
  ▼ (mahasiswa submit)
SUBMITTED
  │
  ▼ (auto-advance)
WAITING_REQUEST_LETTER  ← mahasiswa download & ttd surat
  │
  ▼ (mahasiswa upload signed)
REQUEST_LETTER_UPLOADED
  │
  ▼ (mahasiswa submit untuk review)
UNDER_REVIEW
  │
  ├──▶ REJECTED  ─── (mahasiswa revisi data, upload ulang) ──▶ kembali ke WAITING_REQUEST_LETTER
  │
  ▼ (admin setujui)
APPROVED
  │
  ▼ (admin assign dosen pembimbing)
SUPERVISOR_ASSIGNED
  │
  ▼ (admin generate surat penerimaan)
ACCEPTANCE_LETTER_READY
  │
  ▼ (admin mulai magang)
ONGOING  ← KRS konversi di-inject otomatis; kehadiran otomatis HADIR
  │
  ▼ (admin tandai selesai)
COMPLETED
  │
  ▼ (admin input nilai)
GRADED
  │
  ▼ (admin tutup)
CLOSED
```

### Warna Badge Status

| Status | Warna |
|--------|-------|
| Draft | Abu-abu |
| Submitted / Waiting Letter | Biru |
| Request Uploaded | Indigo |
| Under Review | Kuning |
| Approved | Hijau muda |
| Rejected | Merah |
| Supervisor Assigned | Ungu muda |
| Acceptance Ready | Teal |
| Ongoing | Hijau |
| Completed | Biru tua |
| Graded | Ungu |
| Closed | Abu-abu gelap |

---

## 3. Panduan Mahasiswa

### 3.1 Membuat Pengajuan Magang

1. Login ke **Portal Mahasiswa**
2. Klik menu **Magang** di sidebar kiri
3. Klik tombol **Ajukan Magang**
4. Isi formulir:
   - **Nama Instansi** *(wajib)*: Nama perusahaan/lembaga tempat magang
   - **Alamat Instansi** *(wajib)*: Alamat lengkap
   - **Posisi / Bagian**: Jabatan selama magang (opsional)
   - **Tanggal Mulai & Selesai** *(wajib)*
   - **Deskripsi Kegiatan**: Gambaran umum pekerjaan
   - **Pembimbing Lapangan**: Nama dan no. telp supervisor di instansi
   - **Dokumen Pendukung**: Upload surat/dokumen dari instansi (PDF/JPG, maks 5MB)
5. Klik **Simpan Draft**

> **Info:** Data tersimpan sebagai Draft. Anda bisa edit sebelum submit.

---

### 3.2 Melanjutkan Alur Pengajuan

Setelah draft tersimpan, ikuti tahapan berikut dari halaman **Detail Magang**:

#### Tahap 1 — Submit Pengajuan
- Klik **Submit Pengajuan**
- Status berubah menjadi *Waiting Request Letter*

#### Tahap 2 — Download & Tanda Tangani Surat
- Klik **Download Surat Permohonan**
- Surat DOCX akan ter-download (sudah terisi data otomatis)
- Cetak, tanda tangani, scan ulang ke PDF/JPG
- Klik **Upload Signed** → pilih file hasil scan

#### Tahap 3 — Kirim untuk Review Admin
- Setelah upload berhasil, klik **Kirim untuk Review Admin**
- Status berubah ke *Under Review*; admin akan memproses

#### Jika Ditolak
- Anda akan melihat **Alasan Penolakan** di halaman detail
- Edit data jika diperlukan → upload surat baru → kirim ulang

---

### 3.3 Selama Magang Berlangsung

Ketika status sudah **Ongoing**:

- **Download Surat Penerimaan** — klik tombol untuk mendapatkan surat resmi penugasan
- **Logbook Harian** — tambahkan entri logbook setiap hari:
  1. Klik **+ Tambah Entri** pada bagian Logbook
  2. Isi tanggal dan deskripsi kegiatan
  3. Klik **Simpan**
- **Kehadiran di MK Konversi** — secara otomatis dianggap HADIR (tidak perlu scan QR atau presensi manual)

---

### 3.4 Setelah Magang Selesai

- Admin akan menandai status ke *Completed*, lalu menginput nilai
- Nilai akan muncul di **KHS** (Kartu Hasil Studi) seperti mata kuliah biasa
- Status akhir: *Graded* → *Closed*

---

## 4. Panduan Admin / Akademik

### 4.1 Melihat Daftar Pengajuan

1. Login ke **Admin Panel**
2. Klik **Akademik → Magang Mahasiswa** di sidebar
3. Gunakan filter **Status** dan **Search** (NIM/Nama) untuk menyaring data

**Badge angka** di sidebar menunjukkan jumlah pengajuan yang sedang *Under Review*.

---

### 4.2 Proses Review & Approval

1. Klik **Detail** pada baris pengajuan yang berstatus *Under Review*
2. Periksa data magang dan dokumen surat yang diupload mahasiswa
3. Pilih aksi:
   - **✓ Setujui** — lanjut ke status *Approved*
   - **✗ Tolak** — isi alasan penolakan, klik **Kirim Penolakan**

---

### 4.3 Assign Dosen Pembimbing

Setelah disetujui (status *Approved*):

1. Di halaman detail, pilih dosen dari dropdown **Pilih Dosen**
2. Klik **Tetapkan Pembimbing**
3. Status berubah ke *Supervisor Assigned*

---

### 4.4 Generate Surat Penerimaan

Setelah dosen ditetapkan:

1. Klik **Generate Surat Penerimaan**
2. Sistem otomatis membuat DOCX dari template
3. Status berubah ke *Acceptance Letter Ready*
4. Mahasiswa bisa download surat dari portal mereka

> **Prasyarat:** File `docs/Surat Penerimaan Magang.docx` harus ada (lihat [Section 8](#8-setup-template-surat-docx))

---

### 4.5 Mapping Mata Kuliah Konversi

Sebelum atau setelah magang dimulai, admin perlu menetapkan MK yang akan dikonversi:

1. Di halaman detail, klik **Edit MK Konversi**
2. Klik **+ Tambah MK** → pilih MK dari dropdown → isi SKS
3. Perhatikan indikator **Total SKS** (maksimal 16 SKS)
4. Klik **Simpan Mapping**

---

### 4.6 Mulai Magang (ONGOING)

1. Klik **Mulai Magang**
2. Sistem otomatis:
   - Mengubah status ke *Ongoing*
   - **Meng-inject KRS** konversi untuk setiap MK yang sudah di-mapping
   - Mahasiswa otomatis dianggap **HADIR** di semua pertemuan MK konversi tersebut

---

### 4.7 Tandai Selesai & Input Nilai

1. Klik **Tandai Selesai** → status ke *Completed*
2. Klik **Input Nilai**
3. Masukkan nilai akhir (0–100) untuk masing-masing MK konversi
4. Sistem otomatis menghitung grade (A/AB/B/BC/C/D/E) berdasarkan tabel:

| Nilai | Grade | Bobot |
|-------|-------|-------|
| ≥ 80  | A     | 4.00  |
| ≥ 75  | AB    | 3.50  |
| ≥ 70  | B     | 3.00  |
| ≥ 65  | BC    | 2.50  |
| ≥ 60  | C     | 2.00  |
| ≥ 45  | D     | 1.00  |
| < 45  | E     | 0.00  |

5. Klik **Simpan Nilai** → status ke *Graded*
6. Klik **Tutup** → status ke *Closed* (final)

---

### 4.8 Unduhan Dokumen

| Dokumen | Cara |
|---------|------|
| Surat Permohonan (generated) | Download lewat halaman admin show |
| Surat yang sudah ditandatangani mahasiswa | Tersedia di halaman detail (path `request_letter_signed_path`) |
| Surat Penerimaan | Download lewat halaman admin show |

---

## 5. Panduan Dosen Pembimbing

### 5.1 Melihat Mahasiswa Bimbingan

1. Login ke **Portal Dosen**
2. Klik **Bimbingan Magang** di sidebar
3. Daftar mahasiswa yang di-assign ke Anda akan muncul

---

### 5.2 Menambah Catatan Bimbingan

1. Klik nama mahasiswa untuk masuk ke halaman detail
2. Klik **+ Tambah Catatan** di bagian Logbook
3. Isi:
   - **Tanggal**
   - **Kegiatan Mahasiswa** (opsional)
   - **Catatan / Feedback Dosen** *(wajib)*
4. Klik **Simpan Catatan**

### 5.3 Merespons Logbook Mahasiswa

Untuk entri yang diisi mahasiswa namun belum ada catatan dosen:
- Di bawah entri tersebut, ketik feedback di kolom teks kecil
- Klik **Kirim**

---

## 6. Integrasi Sistem

### 6.1 KRS Otomatis

Saat admin klik **Mulai Magang**, service `InternshipKrsService` akan:
- Mencari mapping MK yang sudah ditetapkan admin
- Membuat entri `krs` dengan flag `is_internship_conversion = true`
- Menghubungkan ke `KelasMataKuliah` aktif jika tersedia

### 6.2 Kehadiran Otomatis

`InternshipAttendanceResolver` menganggap mahasiswa **HADIR** jika:
- KRS entry memiliki `is_internship_conversion = true`
- Internship terkait berstatus `ongoing`

Ini berlaku di semua laporan presensi (view dosen & admin).

### 6.3 Integrasi KHS / Nilai

Nilai yang diinput admin via `InternshipGradingService` akan tersimpan ke tabel `nilais`:
- Semua komponen nilai (partisipatif, proyek, quiz, tugas, uts, uas) = nilai akhir
- `is_published = true` otomatis saat disimpan
- Muncul di KHS mahasiswa seperti MK biasa

---

## 7. Struktur Database

### Tabel `internships`

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `mahasiswa_id` | FK | Mahasiswa yang magang |
| `semester_id` | FK | Semester aktif |
| `status` | enum | Status workflow (13 nilai) |
| `instansi` | string | Nama perusahaan/lembaga |
| `alamat_instansi` | text | Alamat instansi |
| `posisi` | string | Posisi/jabatan |
| `periode_mulai` | date | Tanggal mulai magang |
| `periode_selesai` | date | Tanggal selesai magang |
| `supervisor_dosen_id` | FK nullable | Dosen pembimbing |
| `converted_sks` | int | Total SKS diklaim (otomatis dari mapping) |
| `request_letter_generated_path` | string | Path surat permohonan |
| `request_letter_signed_path` | string | Path surat yang sudah ditandatangani |
| `acceptance_letter_path` | string | Path surat penerimaan |
| `revision_no` | int | Nomor revisi (0 = belum pernah revisi) |

### Tabel `internship_course_mappings`

| Kolom | Keterangan |
|-------|-----------|
| `internship_id` | FK ke internships |
| `mata_kuliah_id` | FK ke mata_kuliahs |
| `sks` | Jumlah SKS untuk MK ini |

### Tabel `internship_logbooks`

| Kolom | Keterangan |
|-------|-----------|
| `internship_id` | FK ke internships |
| `tanggal` | Tanggal bimbingan |
| `kegiatan` | Kegiatan mahasiswa |
| `catatan_dosen` | Feedback dosen |
| `created_by_role` | `'mahasiswa'` atau `'dosen'` |

### Tambahan Kolom di Tabel `krs`

| Kolom | Keterangan |
|-------|-----------|
| `internship_id` | FK ke internships (nullable) |
| `is_internship_conversion` | Boolean; `true` jika MK konversi magang |

---

## 8. Setup Template Surat DOCX

Sistem membutuhkan 2 file template di folder `docs/`:

### `docs/Surat Permohonan Magang.docx`

Template surat permohonan yang akan dikirim ke instansi. Gunakan placeholder berikut di dalam dokumen Word:

| Placeholder | Data |
|-------------|------|
| `${nama}` | Nama mahasiswa |
| `${nim}` | NIM mahasiswa |
| `${prodi}` | Program studi |
| `${semester}` | Semester mahasiswa saat ini |
| `${tahun_ajaran}` | Contoh: `2025/2026` |
| `${instansi}` | Nama instansi tujuan |
| `${alamat_instansi}` | Alamat instansi |
| `${posisi}` | Posisi yang dituju |
| `${periode_mulai}` | Tanggal mulai magang |
| `${periode_selesai}` | Tanggal selesai magang |
| `${tanggal}` | Tanggal surat dibuat |
| `${no_hp}` | No. HP mahasiswa |

### `docs/Surat Penerimaan Magang.docx`

Template surat resmi kampus untuk penerimaan/penugasan dosen. Selain placeholder di atas, tambahkan:

| Placeholder | Data |
|-------------|------|
| `${nama_pembimbing}` | Nama dosen pembimbing |
| `${nidn_pembimbing}` | NIDN dosen pembimbing |
| `${pembimbing_lapangan}` | Nama pembimbing dari instansi |
| `${konversi_sks}` | Total SKS konversi |

### Cara Membuat Template

1. Buka Microsoft Word / LibreOffice Writer
2. Buat surat sesuai kop kampus
3. Sisipkan placeholder persis seperti di atas (termasuk `${...}`)
4. Simpan sebagai format `.docx`
5. Letakkan di folder `docs/` (root project)

---

## 9. Referensi File

### File Baru yang Dibuat

| File | Keterangan |
|------|-----------|
| `database/migrations/2026_03_04_000001_create_internships_table.php` | Migration 4 tabel |
| `app/Models/Internship.php` | Model utama + state machine |
| `app/Models/InternshipCourseMapping.php` | Mapping MK konversi |
| `app/Models/InternshipRevision.php` | Riwayat revisi |
| `app/Models/InternshipLogbook.php` | Logbook bimbingan |
| `app/Services/InternshipWorkflowService.php` | Orkestrasi semua transisi |
| `app/Services/InternshipLetterService.php` | Generate DOCX surat |
| `app/Services/InternshipKrsService.php` | Inject MK konversi ke KRS |
| `app/Services/InternshipAttendanceResolver.php` | Logika hadir otomatis |
| `app/Services/InternshipGradingService.php` | Input & publish nilai |
| `app/Policies/InternshipPolicy.php` | Otorisasi akses |
| `app/Http/Controllers/Mahasiswa/InternshipController.php` | Controller mahasiswa |
| `app/Http/Controllers/Admin/InternshipController.php` | Controller admin |
| `app/Http/Controllers/Dosen/InternshipController.php` | Controller dosen |
| `resources/views/page/mahasiswa/magang/index.blade.php` | Halaman daftar (mahasiswa) |
| `resources/views/page/mahasiswa/magang/create.blade.php` | Halaman form buat |
| `resources/views/page/mahasiswa/magang/show.blade.php` | Halaman detail (mahasiswa) |
| `resources/views/page/mahasiswa/magang/edit.blade.php` | Halaman edit |
| `resources/views/admin/magang/index.blade.php` | Halaman daftar (admin) |
| `resources/views/admin/magang/show.blade.php` | Halaman detail (admin) |
| `resources/views/page/dosen/magang/index.blade.php` | Halaman daftar (dosen) |
| `resources/views/page/dosen/magang/show.blade.php` | Halaman detail (dosen) |
| `docs/MODUL_MAGANG.md` | *Dokumen ini* |

### File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Models/Mahasiswa.php` | Tambah relasi `internships()` dan `activeInternship()` |
| `app/Models/Dosen.php` | Tambah relasi `internshipSupervisions()` |
| `app/Models/Krs.php` | Tambah field `internship_id`, `is_internship_conversion` di fillable + relasi `internship()` |
| `routes/web.php` | Tambah 28 route baru |
| `resources/views/layouts/partials/sidebar-mahasiswa.blade.php` | Tambah menu Magang |
| `resources/views/admin/sidebar-admin.blade.php` | Tambah menu Magang Mahasiswa |
| `resources/views/layouts/partials/sidebar.blade.php` | Tambah menu Bimbingan Magang (dosen) |

### URL Penting

| Role | URL | Keterangan |
|------|-----|-----------|
| Mahasiswa | `/mahasiswa/magang` | Daftar pengajuan |
| Mahasiswa | `/mahasiswa/magang/create` | Buat pengajuan baru |
| Admin | `/admin/magang` | Manajemen semua magang |
| Dosen | `/dosen/magang` | Daftar bimbingan |
