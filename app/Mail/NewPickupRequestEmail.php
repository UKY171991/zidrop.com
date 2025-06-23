<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPickupRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;
    private $pickup;


    public function __construct($merchant,$pickup)
    {
        $this->merchant = $merchant;
        $this->pickup = $pickup;
    }

    public function build()
    {
        $merchant = $this->merchant;
        $pickup = $this->pickup;
        return $this
            ->subject('New Pickup Request')
            ->view('mail.new-pickup-request', compact('merchant', 'pickup'));
    }
}
