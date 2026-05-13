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
        protected string $credentialType = 'student', // 'student', 'parents', or 'both'
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
            'credential_type' => $this->credentialType,
            'sender_id' => $this->senderId,
        ]);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($this->mahasiswaIds as $mahasiswaId) {
            try {
                $mahasiswa = Mahasiswa::with('user', 'parents.user')->find($mahasiswaId);

                if (!$mahasiswa || !$mahasiswa->user) {
                    $failedCount++;
                    $errors[] = "Mahasiswa ID {$mahasiswaId}: User tidak ditemukan";
                    continue;
                }

                // Collect recipient emails based on credentialType
                $recipients = [];
                
                if ($this->credentialType === 'student' || $this->credentialType === 'both') {
                    $studentEmail = $mahasiswa->email_pribadi ?: $mahasiswa->email_kampus;
                    if ($studentEmail) {
                        $tempPassword = \Str::random(10);
                        if ($mahasiswa->user) {
                            $mahasiswa->user->update([
                                'password' => \Illuminate\Support\Facades\Hash::make($tempPassword)
                            ]);
                        }
                        
                        $recipients[] = [
                            'email' => $studentEmail,
                            'type' => 'student',
                            'password' => $tempPassword,
                            'login_email' => $mahasiswa->email_kampus,
                            'name' => $mahasiswa->user->name ?? $mahasiswa->nama,
                            'is_parent' => false,
                        ];
                    }
                }
                
                if ($this->credentialType === 'parents' || $this->credentialType === 'both') {
                    // Get parent emails
                    $lastParentPassword = null;
                    $lastParentLoginEmail = null;
                    
                    foreach ($mahasiswa->parents as $parent) {
                        if ($parent->user && $parent->user->email) {
                            $parentTempPassword = \Str::random(10);
                            $parent->user->update([
                                'password' => \Illuminate\Support\Facades\Hash::make($parentTempPassword)
                            ]);
                            
                            $lastParentPassword = $parentTempPassword;
                            $lastParentLoginEmail = $parent->user->email;
                            
                            $recipients[] = [
                                'email' => $parent->user->email,
                                'type' => 'parent',
                                'password' => $parentTempPassword,
                                'login_email' => $parent->user->email,
                                'name' => $parent->user->name ?? 'Orang Tua',
                                'is_parent' => true,
                            ];
                        }
                    }
                    
                    // Also send to student's personal email when sending to parents
                    if ($this->credentialType === 'parents' && $mahasiswa->email_pribadi && $lastParentPassword) {
                        $recipients[] = [
                            'email' => $mahasiswa->email_pribadi,
                            'type' => 'student',
                            'password' => $lastParentPassword,
                            'login_email' => $lastParentLoginEmail,
                            'name' => 'Tembusan Akun Orang Tua',
                            'is_parent' => true,
                        ];
                    }
                }

                // Send to each recipient
                if (empty($recipients)) {
                    $failedCount++;
                    $errors[] = "Mahasiswa ID {$mahasiswaId}: Tidak ada alamat email recipient";
                    continue;
                }

                foreach ($recipients as $recipient) {
                    try {
                        // Kirim email dengan credentials
                        Mail::to($recipient['email'])->send(
                            new SendCredentialsMail(
                                $mahasiswa, 
                                $recipient['password'],
                                $this->customSubject,
                                $this->customGreeting,
                                $this->customMessage,
                                $recipient['login_email'],
                                $recipient['is_parent'],
                                $recipient['name']
                            )
                        );

                        $successCount++;

                        // Log email blast record
                        $this->logBlastEmail(
                            $mahasiswaId,
                            $recipient['email'],
                            true,
                            null,
                            $recipient['type']
                        );

                        Log::info("[CREDENTIALS BLAST] Email terkirim", [
                            'mahasiswa_id' => $mahasiswaId,
                            'email_target' => $recipient['email'],
                            'recipient_type' => $recipient['type'],
                        ]);
                    } catch (\Throwable $e) {
                        $failedCount++;
                        $errorMsg = $e->getMessage();
                        $errors[] = "Mahasiswa ID {$mahasiswaId} ({$recipient['type']}): {$errorMsg}";

                        $this->logBlastEmail(
                            $mahasiswaId,
                            $recipient['email'],
                            false,
                            $errorMsg,
                            $recipient['type']
                        );

                        Log::error("[CREDENTIALS BLAST] Email gagal", [
                            'mahasiswa_id' => $mahasiswaId,
                            'email_target' => $recipient['email'],
                            'recipient_type' => $recipient['type'],
                            'error' => $errorMsg,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                $failedCount++;
                $errorMsg = $e->getMessage();
                $errors[] = "Mahasiswa ID {$mahasiswaId}: {$errorMsg}";

                Log::error("[CREDENTIALS BLAST] Gagal process mahasiswa", [
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
            'credential_type' => $this->credentialType,
            'errors' => $errors,
        ]);
    }

    /**
     * Log email blast ke database
     */
    private function logBlastEmail(int $mahasiswaId, ?string $email, bool $success, ?string $error, ?string $recipientType = null): void
    {
        try {
            DB::table('email_blast_logs')->insert([
                'batch_id' => $this->batchId,
                'mahasiswa_id' => $mahasiswaId,
                'email_sent_to' => $email,
                'subject' => $this->customSubject ?: 'Akun Login SIAKAD - Email dan Password Kampus Anda',
                'success' => $success,
                'error_message' => $error,
                'recipient_type' => $recipientType ?: 'student',
                'sent_by' => $this->senderId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("[CREDENTIALS BLAST] Gagal log email", [
                'mahasiswa_id' => $mahasiswaId,
                'email' => $email,
                'recipient_type' => $recipientType,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
