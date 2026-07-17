# Laporan Ringkas Perbaikan Sistem SIAKAD - 17 July 2026

Laporan ini ditulis menggunakan bahasa yang sederhana agar mudah dipahami oleh tim non-teknis / orang awam. Semua pekerjaan di bawah ini telah selesai diterapkan, diuji, dan berjalan dengan baik di server.

---

## 📅 Rangkuman Perbaikan Hari Ini

### 1. Penyatuan Data Kelas Perkuliahan (Satu Pintu Database)
*   **Penjelasan:** Kami menyatukan tempat penyimpanan data kelas yang sebelumnya tumpang tindih ke satu tabel tunggal agar jadwal kuliah, absensi, dan nilai mahasiswa tersinkronisasi dengan aman dan terhindar dari data ganda.

### 2. Perbaikan Halaman Detail Kelas & Jadwal Kuliah
*   **Penjelasan:** Memperbaiki seluruh halaman detail kelas untuk dosen dan mahasiswa agar tidak terjadi error saat dibuka akibat perubahan struktur data kelas yang baru.

### 3. Pembersihan Berkas & Sistem Sampah (Clean Up)
*   **Penjelasan:** Menghapus 7 skrip perbaikan database lama yang sudah tidak digunakan lagi agar ukuran penyimpanan aplikasi lebih hemat dan kode program menjadi lebih bersih.

### 4. Peningkatan Kecepatan Halaman Nilai Tugas Dosen
*   **Penjelasan:** Mengurangi beban database pada halaman input nilai tugas sehingga halaman tersebut dapat terbuka jauh lebih cepat meskipun kelas tersebut memiliki banyak mahasiswa.

### 5. Peningkatan Sistem Log Audit Keamanan (Deteksi Perangkat)
*   **Penjelasan:**
    *   Sistem sekarang secara otomatis mendeteksi browser (Chrome, Firefox, Safari) dan sistem operasi (Windows, macOS, Android, iOS) yang digunakan oleh user saat mengakses aplikasi.
    *   Halaman riwayat aktivitas kini dilengkapi pencarian berdasarkan hak akses (Role), alamat IP, serta rentang tanggal tertentu.
    *   Ditambahkan kolom "Perangkat" langsung di tabel utama sehingga admin dapat langsung memantau dari gawai mana suatu aktivitas dilakukan.
