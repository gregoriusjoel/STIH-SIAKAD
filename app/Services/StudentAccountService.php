<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentAccountService
{
    public function __construct(
        protected EmailService $emailService
    ) {}

    /**
     * Otomasi pembuatan/update akun mahasiswa
     * - Generate email kampus jika belum ada
     * - Set password default dari NIM
     * - Create atau update user account
     * 
     * @param Mahasiswa $mahasiswa
     * @param bool $forceRegenerateEmail Force regenerate email kampus
     * @return array{mahasiswa: Mahasiswa, user: User, email_kampus: string, password_set: bool}
     */
    public function automateStudentAccount(Mahasiswa $mahasiswa, bool $forceRegenerateEmail = false): array
    {
        return DB::transaction(function () use ($mahasiswa, $forceRegenerateEmail) {
            // 1. Generate email kampus jika belum ada atau force
            if ($forceRegenerateEmail || !$mahasiswa->email_kampus) {
                $emailKampus = $this->emailService->generateCampusEmail(
                    $mahasiswa->nama,
                    $mahasiswa->id
                );
                $mahasiswa->update(['email_kampus' => $emailKampus]);
                $mahasiswa->refresh();
            } else {
                $emailKampus = $mahasiswa->email_kampus;
            }

            // 2. Set email_aktif default ke 'kampus' jika belum ada email_pribadi
            if (!$mahasiswa->email_pribadi && $mahasiswa->email_aktif !== 'kampus') {
                $mahasiswa->update(['email_aktif' => 'kampus']);
                $mahasiswa->refresh();
            }

            // 3. Generate password dari NIM (harus di-hash)
            $defaultPassword = $mahasiswa->nim;
            $hashedPassword = Hash::make($defaultPassword);

            // 4. Tentukan user email dari email_aktif
            $userEmail = $this->emailService->getActiveEmail($mahasiswa);
            if (!$userEmail) {
                throw new \RuntimeException(
                    "Mahasiswa {$mahasiswa->nim} tidak memiliki email yang valid"
                );
            }

            // 5. Cek atau buat akun User
            $user = User::where('email', $userEmail)->first();

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'name' => $mahasiswa->nama,
                    'email' => $userEmail,
                    'password' => $hashedPassword,
                ]);
            } else {
                // Update user yang sudah ada
                $user->update([
                    'password' => $hashedPassword,
                ]);
            }

            // 6. Link user ke mahasiswa
            if (!$mahasiswa->user_id) {
                $mahasiswa->update(['user_id' => $user->id]);
            }

            // 7. Mark password sebagai default & set automation timestamp
            $mahasiswa->update([
                'is_default_password' => true,
                'account_automation_at' => now(),
            ]);

            return [
                'mahasiswa' => $mahasiswa->fresh(),
                'user' => $user->fresh(),
                'email_kampus' => $emailKampus,
                'password_set' => true,
            ];
        });
    }

    /**
     * Bulk otomasi untuk multiple mahasiswa
     * Digunakan saat migrasi atau proses batch
     * 
     * @param array $mahasiswaIds Array dari mahasiswa IDs
     * @param callable|null $progressCallback Callback untuk tracking progress
     * @return array{success: int, failed: int, errors: array}
     */
    public function bulkAutomateStudents(array $mahasiswaIds, ?callable $progressCallback = null): array
    {
        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($mahasiswaIds as $mahasiswaId) {
            try {
                $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
                $this->automateStudentAccount($mahasiswa);
                $success++;

                if ($progressCallback) {
                    $progressCallback($mahasiswaId, true, null);
                }
            } catch (\Throwable $e) {
                $failed++;
                $errorMsg = "Mahasiswa ID {$mahasiswaId}: {$e->getMessage()}";
                $errors[] = $errorMsg;

                if ($progressCallback) {
                    $progressCallback($mahasiswaId, false, $e->getMessage());
                }
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }

    /**
     * Force reset password untuk mahasiswa (set ke NIM lagi)
     * Digunakan jika mahasiswa lupa password
     * 
     * @param Mahasiswa $mahasiswa
     */
    public function resetPasswordToDefault(Mahasiswa $mahasiswa): void
    {
        $hashedPassword = Hash::make($mahasiswa->nim);

        if ($mahasiswa->user) {
            $mahasiswa->user->update(['password' => $hashedPassword]);
        }

        $mahasiswa->update(['is_default_password' => true]);
    }

    /**
     * Check apakah mahasiswa sudah selesai setup akun
     * (email kampus sudah generate & user sudah ada)
     * 
     * @param Mahasiswa $mahasiswa
     * @return bool
     */
    public function isAccountSetupComplete(Mahasiswa $mahasiswa): bool
    {
        return $mahasiswa->email_kampus !== null
            && $mahasiswa->user_id !== null;
    }

    /**
     * Get account setup status untuk mahasiswa
     * 
     * @param Mahasiswa $mahasiswa
     * @return array
     */
    public function getAccountStatus(Mahasiswa $mahasiswa): array
    {
        $mahasiswa->load('user');

        return [
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'email_lama' => $mahasiswa->email,
            'email_pribadi' => $mahasiswa->email_pribadi,
            'email_pribadi_verified' => $mahasiswa->email_pribadi_verified_at !== null,
            'email_kampus' => $mahasiswa->email_kampus,
            'email_aktif' => $mahasiswa->email_aktif,
            'active_email' => $this->emailService->getActiveEmail($mahasiswa),
            'user_exists' => $mahasiswa->user !== null,
            'is_default_password' => $mahasiswa->is_default_password,
            'account_automation_at' => $mahasiswa->account_automation_at,
        ];
    }
}
