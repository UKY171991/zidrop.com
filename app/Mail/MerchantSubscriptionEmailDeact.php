<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantSubscriptionEmailDeact extends Mailable
{
    use Queueable, SerializesModels;

    private $merchant;
    private $plan;
    private $history;
    private $person;
    private $merchantSubscriptions;

    public function __construct($merchant, $plan, $history = null, $person, $merchantSubscriptions)
    {
        $this->merchant = $merchant;
        $this->plan = $plan;
        $this->history = $history;
        $this->person = $person;
        $this->merchantSubscriptions = $merchantSubscriptions;
    }

    public function build()
    {
        $title = $this->person === 'merchant' ? $this->merchant->companyName : 'Admin';

        return $this
            ->subject('Subscription Plan Dectivate')
            ->view('mail.subscription_mail_deactivation', [
                'merchant' => $this->merchant,
                'plan' => $this->plan,
                'history' => $this->history,
                'title' => $title,
                'MerchantSubscriptions' => $this->merchantSubscriptions,
            ]);
    }
}
