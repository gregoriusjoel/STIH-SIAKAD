<?php

namespace App\Jobs;

use App\Mail\SendCredentialsMail;
use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Job untuk mengirim email kampus & password credentials ke mahasiswa via Blast
 * Dijalankan via queue worker untuk non-blocking
 */
class SendCredentialsBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Batch ID untuk tracking
     */
    protected string $batchId;

    /**
     * Konfigurasi retry
     */
    public int $tries = 3;
    public int $timeout = 300;
    public int $backoff = 60;

    public function __construct(
        protected array $mahasiswaIds,
        protected ?int $senderId = null,
        protected ?string $customSubject = null,
        protected ?string $customGreeting = null,
        protected ?string $customMessage = null,
    ) {
        $this->batchId = uniqid('credentials_blast_');
    }

    /**
     * Execute the job
     */
    public function handle(): void
    {
        Log::info("[CREDENTIALS BLAST] Mulai mengirim ke " . count($this->mahasiswaIds) . " mahasiswa", [
            'batch_id' => $this->batchId,
            'sender_id' => $this->senderId,
        ]);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($this->mahasiswaIds as $mahasiswaId) {
            try {
                $mahasiswa = Mahasiswa::with('user')->find($mahasiswaId);

                if (!$mahasiswa || !$mahasiswa->user) {
                    $failedCount++;
                    $errors[] = "Mahasiswa ID {$mahasiswaId}: User tidak ditemukan";
                    continue;
                }

                // Generate random password
                $tempPassword = \Str::random(10);

                // Update password user di database
                $mahasiswa->user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($tempPassword)
                ]);

                $targetEmail = $mahasiswa->email_pribadi ?: $mahasiswa->email_kampus;

                // Kirim email dengan credentials ke email target
                Mail::to($targetEmail)->send(
                    new SendCredentialsMail(
                        $mahasiswa, 
                        $tempPassword,
                        $this->customSubject,
                        $this->customGreeting,
                        $this->customMessage
                    )
                );

                $successCount++;

                // Log email blast record
                $this->logBlastEmail(
                    $mahasiswaId,
                    $targetEmail,
                    true,
                    null
                );

                Log::info("[CREDENTIALS BLAST] Email terkirim", [
                    'mahasiswa_id' => $mahasiswaId,
                    'email_target' => $targetEmail,
                ]);
            } catch (\Throwable $e) {
                $failedCount++;
                $errorMsg = $e->getMessage();
                $errors[] = "Mahasiswa ID {$mahasiswaId}: {$errorMsg}";

                $this->logBlastEmail(
                    $mahasiswaId,
                    null,
                    false,
                    $errorMsg
                );

                Log::error("[CREDENTIALS BLAST] Email gagal", [
                    'mahasiswa_id' => $mahasiswaId,
                    'error' => $errorMsg,
                ]);
            }
        }

        Log::info("[CREDENTIALS BLAST] Selesai", [
            'batch_id' => $this->batchId,
            'total' => count($this->mahasiswaIds),
            'success' => $successCount,
            'failed' => $failedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Log email blast ke database
     */
    private function logBlastEmail(int $mahasiswaId, ?string $email, bool $success, ?string $error): void
    {
        try {
            DB::table('email_blast_logs')->insert([
                'batch_id' => $this->batchId,
                'mahasiswa_id' => $mahasiswaId,
                'email_sent_to' => $email,
                'subject' => $this->customSubject ?: 'Akun Login SIAKAD - Email dan Password Kampus Anda',
                'success' => $success,
                'error_message' => $error,
                'sent_by' => $this->senderId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("[CREDENTIALS BLAST] Gagal log email", [
                'mahasiswa_id' => $mahasiswaId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
