<?php

namespace App\Notifications;

use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi untuk verifikasi email pribadi mahasiswa
 */
class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Mahasiswa $mahasiswa
    ) {
        $this->onQueue('emails');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->getVerificationUrl();

        return (new MailMessage)
            ->subject('Verifikasi Email Pribadi - SIAKAD Adhyaksa')
            ->greeting("Halo {$this->mahasiswa->nama},")
            ->line('Kami meminta Anda untuk memverifikasi email pribadi Anda.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Link verifikasi ini berlaku selama 60 menit.')
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.')
            ->salutation('Salam,<br>Sistem SIAKAD Universitas Adhyaksa');
    }

    protected function getVerificationUrl(): string
    {
        return route('verification.verify', [
            'id' => $this->mahasiswa->user->getKey(),
            'hash' => sha1($this->mahasiswa->email_pribadi),
        ]);
    }
}
