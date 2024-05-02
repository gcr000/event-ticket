<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingSendCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    // $booking->otp, $event->name, $customer_name
    public $otp;
    public $event_name;
    public $customer_name;
    public $urlToEvent;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $event_name, $customer_name, $urlToEvent)
    {
        $this->otp = $otp;
        $this->event_name = $event_name;
        $this->customer_name = $customer_name;
        $this->urlToEvent = $urlToEvent;
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Codice conferma prenotazione',
        );
    }

    public function build()
    {
        return $this->subject('Codice conferma prenotazione')->view('emails.booking_otp');
    }
}
