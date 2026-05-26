<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi general untuk blast email ke mahasiswa
 * Digunakan untuk pengumuman, notifikasi, dll
 */
class BlastEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $subject,
        protected string $greeting,
        protected string $message,
        protected ?string $actionUrl = null,
        protected ?string $actionText = null,
        protected array $additionalLines = []
    ) {
        $this->onQueue('emails');
        $this->delay = now()->addSeconds(rand(1, 5)); // Jitter untuk distribute load
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.blast-notification', [
                'subject'     => $this->subject,
                'greeting'    => $this->greeting,
                'messageBody' => $this->message,
                'actionUrl'   => $this->actionUrl,
                'actionText'  => $this->actionText,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
