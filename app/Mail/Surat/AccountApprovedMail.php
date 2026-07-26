<?php

namespace App\Mail\Surat;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun E-Surat Anda Telah Disetujui - Desa Mengeruda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat.account_approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
