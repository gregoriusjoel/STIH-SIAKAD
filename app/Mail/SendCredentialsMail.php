<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCredentialsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Mahasiswa $mahasiswa,
        public string $password, // Password plain text
        public ?string $customSubject = null,
        public ?string $customGreeting = null,
        public ?string $customMessage = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject ?: 'Akun Login SIAKAD - Email dan Password Kampus Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.send-credentials',
            with: [
                'mahasiswa' => $this->mahasiswa,
                'email_kampus' => $this->mahasiswa->email_kampus,
                'password' => $this->password,
                'login_url' => 'https://satu.axiona.id',
                'custom_greeting' => $this->customGreeting,
                'custom_message' => $this->customMessage,
            ],
        );
    }
}
