<?php

namespace App\Mail\Surat;

use App\Models\LetterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLetterRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LetterRequest $letterRequest)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Surat Baru: ' . ($this->letterRequest->template->name ?? 'Surat Desa'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat.new_letter_request',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
