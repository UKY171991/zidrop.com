<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantEmailNotificationForParcelReceive extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;
    private $parcel;

    private $agent;

    public function __construct($merchant, $parcel, $agent)
    {
        $this->merchant = $merchant;
        $this->parcel = $parcel;
        $this->agent = $agent;
    }
    public function build()
    {
        $merchant = $this->merchant;
        $parcel = $this->parcel;
        $agent = $this->agent;
        return $this
            ->subject('Parcel Status Update')
            ->view('mail.parcel-received-by-agent', compact('merchant', 'parcel', 'agent'));
    }
}
