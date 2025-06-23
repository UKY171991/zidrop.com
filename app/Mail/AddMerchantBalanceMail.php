<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddMerchantBalanceMail extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;
    private $topup;

    public function __construct($merchant, $topup)
    {
        $this->merchant = $merchant;
        $this->topup = $topup;
    }
    public function build()
    {
        $merchant = $this->merchant;
        $topup = $this->topup;
        return $this
            ->subject('Balance Add in ZiDrop')
            ->view('mail.merchant-balance-add', compact('merchant', 'topup'));
    }
}
