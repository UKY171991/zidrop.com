<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MerchantSubscriptions;
use App\History;
use Carbon\Carbon;

class DisableExpiredSubscriptions extends Command
{
   
    protected $signature = 'subscriptions:disable-expired';
    protected $description = 'Disable subscriptions that have expired based on registration date and duration';

    public function handle()
    {
        $expiredSubscriptions = MerchantSubscriptions::where('is_active', 1)
            ->whereDate('expired_time', '<=', Carbon::now())
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->is_active = 0;
            $subscription->auto_renew = 0;
            $subscription->disable_by = 'auto-expired';
            $subscription->disable_time = now();
            $subscription->save();

            // History
                $history            = new History();
                $history->name      = "Subscription Expired";
                $history->done_by   = "auto-expired";
                $history->status    = 'Subscription Expired';
                $history->note      = "Disabled subscription for merchant ID: {$subscription->merchant_id}";
                $history->date      =  now();
                $history->save();

            $this->info("Disabled subscription for merchant ID: {$subscription->merchant_id}");
        }

        return Command::SUCCESS;
    }
}
