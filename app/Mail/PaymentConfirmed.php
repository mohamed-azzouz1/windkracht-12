<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The registration instance.
     *
     * @var \App\Models\Registration
     */
    public $registration;

    /**
     * The recipient type (student or instructor).
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
    public function __construct(Registration $registration, string $recipientType = 'student')
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
            subject: 'Betaling Bevestigd - Windkracht 12',
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
            view: 'emails.payment-confirmed',
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
