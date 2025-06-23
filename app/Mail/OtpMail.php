<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $type;

    /**
     * Create a new message instance.
     *
     * @param $otp
     * @param string $type
     */
    public function __construct($otp, $type = 'register')
    {
        $this->otp = $otp;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type === 'reset') {
            return $this->subject('OTP for your Zidrop password reset')->view('mail.otp-reset');
        } else {
            return $this->subject('OTP for your Zidrop sign-up verification')->view('mail.otp');
        }
    }
}
