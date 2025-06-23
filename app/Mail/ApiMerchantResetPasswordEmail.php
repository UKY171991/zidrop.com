<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApiMerchantResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $email;
    public $companyName;


    public function __construct($otp, $email = null, $companyName = '')
    {
        $this->otp = $otp;
        $this->email = $email;
        $this->companyName = $companyName;
    }

    public function build()
    {
        $merchant = (object) [
            'companyName' => $this->companyName,
            'emailAddress' => $this->email,
            'passwordReset' => $this->otp,
        ];
        return $this
            ->subject('Forget Password token')
            ->view('mail.merchant-reset-password', compact('merchant'));
    }
}
