<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MerchantRegisterAlertMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $merchant = $this->merchant;
        // var_dump($merchant);
        // exit();
        return $this->view('mail.merchant-registration-alert', compact('merchant'));
    }
}
