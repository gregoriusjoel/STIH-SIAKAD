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
                    $credentialType = $outbox->credential_type ?? 'student';
                    
                    if ($credentialType === 'parents' || $credentialType === 'both') {
                        // Cek apakah target email adalah email orang tua
                        $parentData = null;
                        foreach ($mahasiswa->parents as $p) {
                            if ($p->user && $p->user->email === $outbox->target_email) {
                                $parentData = $p;
                                break;
                            }
                        }
                        
                        // Jika target_email adalah mahasiswa (bisa jadi mode student, atau mode CC dari parent)
                        $isStudentTarget = ($mahasiswa->email_pribadi === $outbox->target_email || $mahasiswa->email_kampus === $outbox->target_email);
                        
                        if ($parentData) {
                            // Ini adalah email untuk orang tua, generate password untuk orang tua
                            $tempPassword = \Str::random(10);
                            $parentData->user->update([
                                'password' => \Illuminate\Support\Facades\Hash::make($tempPassword)
                            ]);
                            
                            Mail::to($outbox->target_email)->send(
                                new \App\Mail\SendCredentialsMail(
                                    $mahasiswa,
                                    $tempPassword,
                                    $outbox->subject,
                                    $outbox->greeting,
                                    $outbox->message_body,
                                    $parentData->user->email,
                                    true,
                                    $parentData->user->name ?? 'Orang Tua'
                                )
                            );
                            continue; // Skip the rest of the loop block
                        } else if ($credentialType === 'parents' && $isStudentTarget) {
                            // Ini adalah CC untuk mahasiswa di mode 'parents'
                            $firstParent = null;
                            foreach ($mahasiswa->parents as $p) {
                                if ($p->user) {
                                    $firstParent = $p;
                                    break;
                                }
                            }
                            
                            if ($firstParent) {
                                $tempPassword = \Str::random(10);
                                $firstParent->user->update([
                                    'password' => \Illuminate\Support\Facades\Hash::make($tempPassword)
                                ]);
                                
                                Mail::to($outbox->target_email)->send(
                                    new \App\Mail\SendCredentialsMail(
                                        $mahasiswa,
                                        $tempPassword,
                                        $outbox->subject,
                                        $outbox->greeting,
                                        $outbox->message_body,
                                        $firstParent->user->email,
                                        true,
                                        'Tembusan Akun Orang Tua'
                                    )
                                );
                            }
                            continue;
                        }
                    }
                    
                    // Fallback / Mode Student (untuk 'student' atau jika target email di mode 'both' adalah mahasiswa)
                    if (!$mahasiswa->user) {
                        throw new \Exception("Mahasiswa belum memiliki akun (user_id kosong).");
                    }

                    // Buat password random baru untuk mahasiswa
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
                            $outbox->message_body,
                            $mahasiswa->email_kampus,
                            false,
                            $mahasiswa->user->name
                        )
                    );
                } else {
                    // Email Biasa
                    $actionUrl = url('/');
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
