# Laporan Ringkas Perbaikan Sistem SIAKAD - 15 Juli 2026

Laporan ini ditulis menggunakan bahasa yang sederhana agar mudah dipahami oleh tim non-teknis / orang awam. Semua pekerjaan di bawah ini telah selesai diterapkan dan diuji langsung pada sistem.

---

## 📅 Rangkuman Perbaikan Hari Ini

### 1. Perbaikan Tampilan Status Keuangan Mahasiswa (Dashboard)
*   **Masalah Sebelumnya:** Banyak mahasiswa yang sudah melunasi uang kuliah namun di halaman utama (dashboard) mereka tetap muncul tulisan merah **"Belum Bayar"**. Hal ini terjadi karena sistem salah membaca tabel pembukuan lama yang kosong.
*   **Solusi:** Kami sudah memperbaiki sistem pembacaan tersebut. Sekarang, status pembayaran mahasiswa di halaman utama akan langsung berubah menjadi **"Lunas"** atau **"Mencicil"** secara otomatis dan akurat begitu pembayaran mereka diverifikasi.

### 2. Penghapusan Sistem Absensi Lama (Menghindari Kecurangan)
*   **Masalah Sebelumnya:** Ada sistem absensi lama yang memungkinkan mahasiswa mengisi daftar hadir langsung tanpa perlu masuk (login) ke akun mereka terlebih dahulu.
*   **Solusi:** Sistem lama tersebut telah kami hapus secara total. Kini, mahasiswa **wajib login** terlebih dahulu untuk melakukan absensi. Hal ini dilakukan demi keamanan data dan mencegah mahasiswa mengisi kehadiran secara curang.

### 3. Perapihan Gudang Data (Optimasi Database)
*   **Masalah Sebelumnya:** Server database menampung banyak "catatan indeks ganda" yang membuat server bekerja lebih berat setiap kali menyimpan data nilai kuliah atau absensi.
*   **Solusi:** Kami membersihkan dan merapikan catatan indeks ganda tersebut. Selain itu, kami memasang kunci pengaman agar tidak ada data nilai atau absensi yang tersimpan dua kali (dobel). Efeknya, database menjadi lebih ringan, cepat, dan terhindar dari data ganda yang membingungkan.

### 4. Pembersihan Berkas Sampah (Menghemat Penyimpanan Server)
*   **Masalah Sebelumnya:** Terdapat modul tugas akhir versi Bahasa Inggris (*Thesis*) dan data siswa model lama (*Student*) yang tidak pernah dipakai karena sistem kita sepenuhnya menggunakan modul Bahasa Indonesia (*Skripsi*).
*   **Solusi:** Semua berkas sisa dan tidak terpakai tersebut sudah kami buang dari server. Hal ini membuat folder sistem menjadi lebih bersih, hemat ruang penyimpanan, dan mempermudah tim IT dalam merawat sistem di masa depan.

### 5. Halaman Dokumen Kerja Developer yang Lebih Modern
*   **Masalah Sebelumnya:** Halaman panduan kerja developer (`/dev-docs`) langsung memuat file besar secara otomatis saat pertama kali dibuka, yang terkadang membuat halaman menjadi lambat atau macet.
*   **Solusi:** Halaman panduan tersebut kini didesain ulang agar lebih rapi (seperti aplikasi Notion), langsung menampilkan pilihan menu dokumen, dan berjalan dengan sangat cepat tanpa macet.
