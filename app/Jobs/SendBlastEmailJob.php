<?php

namespace App\Jobs;

use App\Models\Mahasiswa;
use App\Notifications\BlastEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk mengirim blast email ke mahasiswa
 * Dijalankan via queue worker untuk non-blocking
 */
class SendBlastEmailJob implements ShouldQueue
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
        protected string $subject,
        protected string $greeting,
        protected string $message,
        protected ?string $actionUrl = null,
        protected ?string $actionText = null,
        protected ?int $senderId = null,
    ) {
        $this->batchId = uniqid('blast_');
    }

    public function setBatchId(string $batchId): self
    {
        $this->batchId = $batchId;
        return $this;
    }

    /**
     * Execute the job
     */
    public function handle(): void
    {
        Log::info("[BLAST EMAIL] Mulai mengirim ke " . count($this->mahasiswaIds) . " mahasiswa", [
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

                $targetEmail = $mahasiswa->email_pribadi ?: $mahasiswa->user->email;

                // Kirim notification via queue
                \Illuminate\Support\Facades\Notification::route('mail', $targetEmail)->notify(
                    new BlastEmailNotification(
                        $this->subject,
                        $this->greeting,
                        $this->message,
                        $this->actionUrl,
                        $this->actionText
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

                Log::error("[BLAST EMAIL] Gagal kirim ke mahasiswa {$mahasiswaId}", [
                    'error' => $errorMsg,
                    'batch_id' => $this->batchId,
                ]);
            }
        }

        Log::info("[BLAST EMAIL] Selesai", [
            'batch_id' => $this->batchId,
            'success' => $successCount,
            'failed' => $failedCount,
            'total' => count($this->mahasiswaIds),
        ]);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("[BLAST EMAIL] Job gagal", [
            'batch_id' => $this->batchId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    /**
     * Log email blast untuk tracking
     */
    protected function logBlastEmail(
        int $mahasiswaId,
        ?string $emailSent,
        bool $success,
        ?string $errorMessage
    ): void {
        try {
            DB::table('email_blast_logs')->insert([
                'batch_id' => $this->batchId,
                'mahasiswa_id' => $mahasiswaId,
                'email_sent_to' => $emailSent,
                'subject' => $this->subject,
                'success' => $success,
                'error_message' => $errorMessage,
                'sent_by' => $this->senderId,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning("[BLAST EMAIL] Gagal logging ke database", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
