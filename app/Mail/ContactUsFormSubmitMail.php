<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsFormSubmitMail extends Mailable
{
    use Queueable, SerializesModels;

    private $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        $contact = $this->contact;
        return $this->subject('Contact Us from ZiDrop')
            ->view('mail.contact-us', compact('contact'));
    }
}
