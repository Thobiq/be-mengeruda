<?php

namespace App\Mail\Surat;

use App\Models\LetterRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LetterReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $downloadUrl;

    public function __construct(public LetterRequest $letterRequest)
    {
        $this->downloadUrl = url('/api/surat/pdf/download/' . $this->letterRequest->id);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Anda Telah Terbit & Siap Diunduh - Desa Mengeruda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat.letter_ready',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
