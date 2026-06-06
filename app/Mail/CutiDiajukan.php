<?php

namespace App\Mail;

use App\Models\Cuti;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CutiDiajukan extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Cuti $cuti) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Pengajuan Cuti Baru – {$this->cuti->user->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cuti_diajukan',
        );
    }
}
