# Panduan Sistem & Arsitektur Teknologi - SIAKAD SATU

Dokumen ini menjelaskan kegunaan sistem SIAKAD SATU (Sistem Akademik Terpadu Universitas Adhyaksa) beserta seluruh tumpukan teknologi (*tech stack*) yang digunakan dalam pengembangannya.

---

## 🏫 1. Tentang Sistem SIAKAD SATU (Untuk Apa Sistem Ini?)

SIAKAD SATU adalah platform **Sistem Informasi Akademik & Finansial Kampus** terpadu yang dirancang untuk mendigitalisasi seluruh administrasi akademik, aktivitas perkuliahan, hingga sistem pembayaran mahasiswa secara real-time dan terintegrasi.

Sistem ini memfasilitasi kebutuhan 5 aktor utama:
*   **Mahasiswa:** Mengisi KRS, mengunduh materi & mengumpulkan tugas e-learning, melakukan absensi kelas, memantau nilai KHS, mengajukan cicilan uang kuliah, melakukan bimbingan skripsi, hingga mendaftar wisuda.
*   **Dosen:** Mengelola jadwal mengajar, menginput nilai UTS/UAS/Tugas, melakukan absensi kelas (QR/GPS), membagikan materi kelas, memantau bimbingan skripsi mahasiswa, serta melihat umpan balik kuesioner dosen.
*   **Orang Tua / Wali:** Memantau kehadiran kuliah, status kelulusan mata kuliah, nilai indeks prestasi (IPK), serta riwayat pembayaran uang kuliah anak secara berkala.
*   **Admin / Staf Akademik:** Menyusun jadwal perkuliahan otomatis, mengelola data dosen & mahasiswa, memvalidasi berkas wisuda, mengumumkan info kampus, serta mengelola template email blast.
*   **Super Admin:** Memiliki kontrol penuh atas konfigurasi sistem, audit log aktivitas pengembang, otorisasi peran (*role permission*), dan pengaturan tahun ajaran/semester aktif.

### 📦 Modul-Modul Utama di SIAKAD SATU:
1.  **Modul Akademik (KRS & KHS):** Pengisian rencana studi mahasiswa, sinkronisasi semester aktif, dan penerbitan Kartu Hasil Studi (KHS).
2.  **Modul Penjadwalan Pintar (Smart Scheduler):** Pembuatan jadwal kuliah otomatis, alokasi ruangan kelas, dan penempatan dosen pengampu dengan deteksi bentrok otomatis.
3.  **Modul Absensi Geofencing (QR & GPS):** Sistem kehadiran digital berbasis kode QR dan validasi titik koordinat GPS lokasi mahasiswa untuk mencegah kecurangan absensi.
4.  **Modul E-Learning & LMS:** Pengunggahan modul materi perkuliahan, pengumpulan tugas mahasiswa, tautan kelas virtual (Zoom/Meet), dan perekaman Berita Acara Perkuliahan (BAP).
5.  **Modul Keuangan Kampus (Invoicing & VA):** Penerbitan tagihan uang kuliah otomatis berbasis Virtual Account, pengajuan dispensasi cicilan pembayaran, dan form verifikasi bukti transfer.
6.  **Modul Tugas Akhir & Kelulusan (Skripsi & Wisuda):** Alur bimbingan skripsi digital, ploting pembimbing, pendaftaran sidang, revisi berkas, hingga validasi kelayakan pendaftaran wisuda.

---

## 🛠️ 2. Stack Teknologi (*Tech Stack*) yang Digunakan

Proyek ini dibangun di atas fondasi teknologi web modern dengan kombinasi performa backend yang tangguh dan frontend yang interaktif:

### A. Backend & Data Engine
*   **Runtime:** **PHP v8.2+** (saat ini berjalan pada runtime PHP 8.5 di server lokal).
*   **Framework:** **Laravel Framework v12.0** (versi mayor terbaru dengan penanganan cache, scheduler, dan route teroptimasi).
*   **Database:** **MySQL** (menyimpan seluruh skema relasional terstruktur).
*   **Libraries / Packages Pendukung:**
    *   `spatie/laravel-permission` (v8.0): Mengatur otorisasi peran (*Roles & Permissions*) secara granular di seluruh Controller dan View.
    *   `simplesoftwareio/simple-qrcode` (v4.2): Membuat gambar kode QR dinamis untuk absensi pertemuan kelas.
    *   `maatwebsite/excel` (v3.1): Membaca dan mengekspor data mahasiswa, dosen, serta kuesioner ke format Excel (`.xlsx`/`.csv`).
    *   `phpoffice/phpword` (v1.4): Menghasilkan file Microsoft Word otomatis untuk keperluan cetak kuesioner.
    *   `barryvdh/laravel-dompdf` (v3.1): Mencetak berkas PDF instan untuk KRS, KHS, dan BAP.
    *   `league/flysystem-aws-s3-v3` (v3.0): Integrasi penyimpanan berkas di cloud storage AWS S3 untuk bukti bayar dan lampiran tugas akhir.

### B. Frontend & Interaktivitas Antarmuka
*   **Compiler / Bundler:** **Vite v7.0** (mempercepat proses kompilasi aset CSS/JS secara instan).
*   **CSS Styling:** **Tailwind CSS v4.0** (framework CSS berbasis utility yang sangat ringan dengan performa rendering optimal).
*   **Interactivity Engine:** **Alpine.js v3.15** (JS framework deklaratif minimalis sebagai pengganti framework berat untuk menangani aksi UI).
*   **Pustaka UI Pendukung:**
    *   `ApexCharts`: Menyajikan visualisasi grafik batang dan lingkaran untuk statistik akademik pada dashboard.
    *   `SweetAlert2`: Menyediakan dialog popup interaktif dan premium untuk notifikasi sistem.
    *   `FullCalendar`: Menyajikan jadwal mingguan kuliah mahasiswa dan jadwal mengajar dosen secara visual.
    *   `TomSelect`: Menghadirkan menu dropdown pilihan berganda dengan filter pencarian instan.
    *   `Flatpickr`: Komponen pemilih tanggal dan waktu (*date-picker*) yang responsif.
    *   `Cropper.js`: Memotong (*crop*) foto profil mahasiswa dan dosen sebelum diunggah ke server.

### C. Ekosistem Pendamping (Project G-Roots)
*   Sistem ini terhubung paralel dengan proyek pendamping **G-Roots** (backend Laravel terpisah di port `8028` dan frontend berbasis React/JS di port dev) sebagai bagian dari infrastruktur terpadu sistem pendidikan kampus.
