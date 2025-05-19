<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;

class RegistrationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The registration instance.
     *
     * @var \App\Models\Registration
     */
    public $registration;

    /**
     * The recipient type (student, instructor, duo).
     *
     * @var string
     */
    public $recipientType;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Registration  $registration
     * @param  string  $recipientType
     * @return void
     */
    public function __construct(Registration $registration, string $recipientType)
    {
        $this->registration = $registration;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Kitesurfles Bevestiging - Windkracht 12',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.registration-confirmed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
