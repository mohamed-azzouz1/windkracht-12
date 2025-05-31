<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonCancelled extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The registration instance.
     *
     * @var Registration
     */
    public $registration;

    /**
     * The cancellation reason.
     *
     * @var string
     */
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Registration $registration, $reason)
    {
        $this->registration = $registration->load(['student.user', 'instructor.user', 'package']);
        $this->reason = $reason ?? $registration->cancellation_reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Annulering van je les bij Windkracht 12')
                    ->markdown('emails.lessons.cancelled');
    }
}
