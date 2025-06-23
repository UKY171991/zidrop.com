<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParcelStatusUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;
    private $parcel;
    private $history;

    public function __construct($merchant, $parcel, $history)
    {
        $this->merchant = $merchant;
        $this->parcel = $parcel;
        $this->history = $history;
    }


    // public function build()
    // {
    //     $merchant = $this->merchant;
    //     $parcel = $this->parcel;
    //     $history = $this->history;
    //     return $this
    //         ->subject('Parcel Status Update')
    //         ->view('mail.parcel-status-update', compact('merchant', 'parcel', 'history'));
    // }
    public function build()
    {
        $merchant = $this->merchant;
        $parcel = $this->parcel;
        $history = $this->history;
        return $this
            ->subject('Parcel Status Update')
            ->view('mail.new_status_update', compact('merchant', 'parcel', 'history'));
    }
}
