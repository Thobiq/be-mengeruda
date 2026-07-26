<?php

namespace App\Mail\Surat;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Akun E-Surat Baru: ' . $this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat.new_registration',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
