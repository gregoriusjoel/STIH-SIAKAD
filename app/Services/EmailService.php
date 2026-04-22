<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Support\Str;

class EmailService
{
    /**
     * Domain untuk email kampus
     */
    protected const CAMPUS_EMAIL_DOMAIN = 'student.stih.ac.id';

    /**
     * Generate email kampus dari nama mahasiswa
     * Format: [nama_tanpa_spasi]@student.stih.ac.id
     * 
     * @param string $nama Nama mahasiswa
     * @param int|null $mahasiswaId ID mahasiswa untuk cek duplikasi
     * @return string Email kampus yang valid & unique
     */
    public function generateCampusEmail(string $nama, ?int $mahasiswaId = null): string
    {
        // Bersihkan nama: lowercase, hapus spasi & karakter khusus
        $baseEmail = $this->sanitizeEmail($nama);

        // Validasi bahwa base email tidak kosong
        if (empty($baseEmail)) {
            throw new \InvalidArgumentException("Nama tidak dapat dibuat email yang valid: {$nama}");
        }

        // Cek jika email sudah ada, tambahkan angka incremental
        $candidateEmail = $baseEmail . '@' . self::CAMPUS_EMAIL_DOMAIN;
        $counter = 1;
        $originalCandidate = $candidateEmail;

        while ($this->emailExists($candidateEmail, $mahasiswaId)) {
            // Format: baseEmail1@domain, baseEmail2@domain, dst
            $candidateEmail = "{$baseEmail}{$counter}@" . self::CAMPUS_EMAIL_DOMAIN;
            $counter++;

            // Safety: jangan loop infinite
            if ($counter > 1000) {
                throw new \RuntimeException("Tidak dapat generate email unik untuk: {$nama}");
            }
        }

        return $candidateEmail;
    }

    /**
     * Sanitize nama menjadi format email: lowercase, no spaces, no special chars
     * 
     * @param string $nama
     * @return string
     */
    public function sanitizeEmail(string $nama): string
    {
        // Lowercase semua
        $sanitized = mb_strtolower($nama, 'UTF-8');

        // Hapus spasi
        $sanitized = str_replace(' ', '', $sanitized);

        // Hapus karakter khusus, hanya keep alfanumerik & underscore
        $sanitized = preg_replace('/[^a-z0-9_]/', '', $sanitized);

        // Hapus underscore di awal/akhir
        $sanitized = trim($sanitized, '_');

        // Jika menjadi kosong, kembalikan string kosong
        return $sanitized ?: '';
    }

    /**
     * Cek apakah email kampus sudah ada di database
     * 
     * @param string $email Email kampus untuk dicek
     * @param int|null $excludeMahasiswaId ID mahasiswa untuk exclude (opsional)
     * @return bool true jika sudah ada, false jika belum
     */
    public function emailExists(string $email, ?int $excludeMahasiswaId = null): bool
    {
        $query = Mahasiswa::where('email_kampus', $email);

        if ($excludeMahasiswaId) {
            $query->where('id', '!=', $excludeMahasiswaId);
        }

        return $query->exists();
    }

    /**
     * Validasi format email pribadi
     * 
     * @param string $email Email pribadi
     * @return bool true jika valid
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Dapatkan email aktif untuk mahasiswa
     * Mengembalikan email yang seharusnya digunakan untuk login & notifikasi
     * 
     * @param Mahasiswa $mahasiswa
     * @return string|null Email aktif, atau null jika tidak ada
     */
    public function getActiveEmail(Mahasiswa $mahasiswa): ?string
    {
        if ($mahasiswa->email_aktif === 'kampus' && $mahasiswa->email_kampus) {
            return $mahasiswa->email_kampus;
        }

        // Default ke email pribadi atau email lama
        if ($mahasiswa->email_pribadi) {
            return $mahasiswa->email_pribadi;
        }

        // Fallback ke email lama
        return $mahasiswa->email;
    }

    /**
     * Update email pribadi mahasiswa dengan validasi
     * 
     * @param Mahasiswa $mahasiswa
     * @param string $email Email pribadi baru
     * @throws \InvalidArgumentException
     */
    public function updateEmailPribadi(Mahasiswa $mahasiswa, string $email): void
    {
        if (!$this->validateEmail($email)) {
            throw new \InvalidArgumentException("Format email tidak valid: {$email}");
        }

        // Cek duplikasi dengan mahasiswa lain
        if (Mahasiswa::where('email_pribadi', $email)
            ->where('id', '!=', $mahasiswa->id)
            ->exists()) {
            throw new \InvalidArgumentException("Email pribadi sudah digunakan mahasiswa lain");
        }

        $mahasiswa->update([
            'email_pribadi' => $email,
            'email_pribadi_verified_at' => null, // Reset verifikasi
        ]);
    }

    /**
     * Set email pribadi sebagai verified
     * 
     * @param Mahasiswa $mahasiswa
     */
    public function markEmailPribadiAsVerified(Mahasiswa $mahasiswa): void
    {
        $mahasiswa->update([
            'email_pribadi_verified_at' => now(),
        ]);
    }

    /**
     * Cek apakah email pribadi sudah diverifikasi
     * 
     * @param Mahasiswa $mahasiswa
     * @return bool
     */
    public function isEmailPribadiVerified(Mahasiswa $mahasiswa): bool
    {
        return $mahasiswa->email_pribadi_verified_at !== null;
    }

    /**
     * Switch email aktif untuk mahasiswa
     * 
     * @param Mahasiswa $mahasiswa
     * @param string $emailType 'pribadi' atau 'kampus'
     * @throws \InvalidArgumentException
     */
    public function switchActiveEmail(Mahasiswa $mahasiswa, string $emailType): void
    {
        if (!in_array($emailType, ['pribadi', 'kampus'])) {
            throw new \InvalidArgumentException("Email type harus 'pribadi' atau 'kampus'");
        }

        if ($emailType === 'kampus' && !$mahasiswa->email_kampus) {
            throw new \InvalidArgumentException("Email kampus belum di-generate");
        }

        $mahasiswa->update([
            'email_aktif' => $emailType,
        ]);
    }
}
