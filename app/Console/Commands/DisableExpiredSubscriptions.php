<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MerchantSubscriptions;
use App\Mail\MerchantSubscriptionEmailDeact;
use App\History;
use App\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DisableExpiredSubscriptions extends Command
{
   
    protected $signature = 'subscriptions:disable-expired';
    protected $description = 'Disable subscriptions that have expired based on registration date and duration';

    public function handle()
    {
         $this->info('? Command is running');
        \Log::info('subscriptions:disable-expired command triggered at ' . now());


        $expiredSubscriptions = MerchantSubscriptions::where('is_active', 1)
            ->whereDate('expired_time', '<=', Carbon::now())
            ->get();
            foreach ($expiredSubscriptions as $subscription) {
                if($subscription->auto_expired == 0) continue;
                $subscription->is_active = 0;
                $subscription->auto_renew = 0;
                $subscription->disable_by = 'auto-expired';
                $subscription->disable_time = now();
                $subscription->update();
                
                
                // Get related models
                $Merchant = $subscription->merchant;
                $SubsPlan = $subscription->plan;
                
                
                // Remove manual permission based on subscription plan
                // Get Merchant
                $MerchantUpdate = Merchant::findOrFail($Merchant->id );
                switch ($subscription->subs_pkg_id ) {
                    case 1: // Starter
                        $MerchantUpdate->cod_cal_permission = 1;
                        $MerchantUpdate->ins_cal_permission = 1;
                        break;
                        
                        case 2: // Premium
                            $MerchantUpdate->cod_cal_permission = 1;
                            $MerchantUpdate->ins_cal_permission = 1;
                            break;
                            
                            default:
                            // Optionally reset or skip updates for unrecognized plan
                            break;
                        }
                        // dd($MerchantUpdate);
                
                $MerchantUpdate->update();
        
                
                // History
                $history            = new History();
                $history->name      = "Subscription Expired";
                $history->done_by   = "auto-expired";
                $history->status    = 'Subscription Expired';
                $history->note      = "Disabled subscription for merchant ID: {$subscription->merchant_id}";
                $history->date      =  now();
                $history->save();
                
                $this->info("Disabled subscription for merchant ID: {$subscription->merchant_id}");
                
                // Send Email Notifications
                try {
                    \Mail::to($Merchant->emailAddress)->send(new MerchantSubscriptionEmailDeact($Merchant, $SubsPlan, $history, 'merchant', $subscription));
                    \Mail::to(env('MAIL_ADMIN_ADDRESS'))->send(new MerchantSubscriptionEmailDeact($Merchant, $SubsPlan, $history, 'admin', $subscription));
                    // dd($history);
            } catch (\Exception $exception) {
                // dd($exception->getMessage()); // ?? shows the actual error message
                Log::warning('Subscription mail failed: ' . $exception->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
