<?php

namespace App\Console\Commands;

use App\Models\EmailOutbox;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BlastEmailNotification;

class ProcessEmailOutbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:process-outbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proses dan kirim email dari antrean outbox (menunggu/terjadwal)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        
        $pendingEmails = EmailOutbox::with('mahasiswa.user')
            ->where('status', 'pending')
            ->where(function ($query) use ($now) {
                $query->whereNull('scheduled_at')
                      ->orWhere('scheduled_at', '<=', $now);
            })
            ->limit(100) // Proses max 100 email per menit agar tidak timeout
            ->get();

        if ($pendingEmails->isEmpty()) {
            $this->info("Tidak ada email di antrean yang perlu diproses saat ini.");
            return;
        }

        $this->info("Ditemukan {$pendingEmails->count()} email di antrean. Memulai pengiriman...");

        foreach ($pendingEmails as $outbox) {
            try {
                $mahasiswa = $outbox->mahasiswa;
                if (!$mahasiswa) {
                    throw new \Exception("Mahasiswa tidak ditemukan.");
                }

                if ($outbox->is_credentials_mode) {
                    // Cek jika user login tidak ada
                    if (!$mahasiswa->user) {
                        throw new \Exception("Mahasiswa belum memiliki akun (user_id kosong).");
                    }

                    // Buat password random baru
                    $tempPassword = \Str::random(10);
                    $mahasiswa->user->update([
                        'password' => \Illuminate\Support\Facades\Hash::make($tempPassword)
                    ]);

                    // Kirim
                    Mail::to($outbox->target_email)->send(
                        new \App\Mail\SendCredentialsMail(
                            $mahasiswa,
                            $tempPassword,
                            $outbox->subject,
                            $outbox->greeting,
                            $outbox->message_body
                        )
                    );
                } else {
                    // Email Biasa
                    $actionUrl = 'https://satu.axiona.id';
                    $actionText = 'Login ke SIAKAD';
                    
                    Notification::route('mail', $outbox->target_email)->notify(
                        new BlastEmailNotification(
                            $outbox->subject ?? 'Informasi SIAKAD',
                            $outbox->greeting ?? 'Halo',
                            $outbox->message_body ?? '',
                            $actionUrl,
                            $actionText
                        )
                    );
                }

                $outbox->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                // Log as successful
                \Illuminate\Support\Facades\DB::table('email_blast_logs')->insert([
                    'batch_id' => $outbox->batch_id,
                    'mahasiswa_id' => $outbox->mahasiswa_id,
                    'email_sent_to' => $outbox->target_email,
                    'subject' => $outbox->subject,
                    'success' => true,
                    'sent_by' => null,
                    'created_at' => now(),
                ]);

            } catch (\Exception $e) {
                $outbox->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                // Log as failed
                \Illuminate\Support\Facades\DB::table('email_blast_logs')->insert([
                    'batch_id' => $outbox->batch_id,
                    'mahasiswa_id' => $outbox->mahasiswa_id,
                    'email_sent_to' => $outbox->target_email,
                    'subject' => $outbox->subject,
                    'success' => false,
                    'error_message' => $e->getMessage(),
                    'sent_by' => null,
                    'created_at' => now(),
                ]);
                
                Log::error("[OUTBOX PROCESSOR] Gagal mengirim email outbox ID {$outbox->id}: " . $e->getMessage());
            }
        }

        $this->info("Proses selesai.");
    }
}
