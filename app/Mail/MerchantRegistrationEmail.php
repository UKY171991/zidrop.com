<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MerchantRegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;

    public function __construct($merchant)
    {
        $this->merchant = $merchant;
    }


    public function build()
    {
        $merchant = $this->merchant;
        return $this->view('mail.merchant-register', compact('merchant'));
    }
}
