<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Package;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $registrations;
    public $invoice;
    public $student;
    public $package;
    public $isDuo;
    public $isDuoRecipient;

    /**
     * Create a new message instance.
     *
     * @param array $registrations
     * @param Invoice $invoice
     * @param Student $student
     * @param Package $package
     * @param bool $isDuo
     * @param bool $isDuoRecipient
     * @return void
     */
    public function __construct($registrations, Invoice $invoice, Student $student, Package $package, bool $isDuo = false, bool $isDuoRecipient = false)
    {
        $this->registrations = $registrations;
        $this->invoice = $invoice;
        $this->student = $student;
        $this->package = $package;
        $this->isDuo = $isDuo;
        $this->isDuoRecipient = $isDuoRecipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isDuoRecipient 
            ? 'Je bent uitgenodigd voor kitesurf lessen bij Windkracht 12' 
            : 'Bevestiging van je reservering bij Windkracht 12';
            
        return $this->subject($subject)
            ->view('emails.reservation_confirmation')
            ->with([
                'registrations' => $this->registrations,
                'invoice' => $this->invoice,
                'student' => $this->student,
                'package' => $this->package,
                'isDuo' => $this->isDuo,
                'isDuoRecipient' => $this->isDuoRecipient,
                'paymentInfo' => [
                    'bankAccount' => 'NL12 RABO 0123 4567 89',
                    'accountName' => 'Windkracht 12 B.V.',
                    'reference' => $this->invoice->invoice_number,
                ]
            ]);
    }
}