<?php
namespace App\Providers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Logo;
use App\Service;
use App\Parcel;
use App\Agent;
use App\Contact;
use App\Deliveryman;
use App\Pickup;
use App\Deliverycharge;
use App\District;
use App\Socialmedia;
use App\Nearestzone;
use Carbon\Carbon;
use App\Parceltype;
use App\Note;
use DB;
use App\Disclamer;
use App\Merchant;
use App\Expense;
use App\ExpenseType;
use App\StatisticsDetails;
use App\City;
use App\Town;
use App\ChargeTarif;
use App\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');

        // Only load view data if not in console or if tables exist
        if (!app()->runningInConsole() || $this->tablesExist()) {
            $this->loadViewData();
        } else {
            $this->loadDefaultViewData();
        }
    }    /**
     * Check if database tables exist
     */
    private function tablesExist()
    {
        try {
            // Check for multiple tables to ensure database is properly set up
            \DB::table('settings')->exists();
            \DB::table('notes')->exists();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Load default view data when database is not available
     */
    private function loadDefaultViewData()
    {
        view()->share('PublicExpenseTypes', collect());
        view()->share('PubliclastExpNUmber', 1);
        view()->share('websettings', null);
        view()->share('merchants', collect());
        view()->share('wcities', collect());
        view()->share('wtowns', collect());
        view()->share('webStatisticsDetails', (object) [
            'total_delivery' => 0,
            'total_customers' => 0,
            'total_years' => 0,
            'total_member' => 0
        ]);
        view()->share('wchargeTarifs', collect());
        view()->share('contact_info', null);
        view()->share('merchantNotice', null);
        view()->share('agentNotice', null);
        view()->share('whitelogo', collect());
        view()->share('darklogo', collect());
        view()->share('faveicon', collect());
        view()->share('services', collect());
        view()->share('newparcel', collect());
        view()->share('processingparcel', collect());
        view()->share('onthewayparcel', collect());
        view()->share('deliverdparcel', collect());
        view()->share('cancelledparcel', collect());
        view()->share('returnprocessing', collect());
        view()->share('returnparcel', collect());
        view()->share('agents', collect());
        view()->share('deliverymen', collect());
        view()->share('newpickup', collect());
        view()->share('pendingpickup', collect());
        view()->share('acceptedpickup', collect());
        view()->share('cancelledpickup', collect());
        view()->share('deliverycharge', collect());
        view()->share('totalamounts', 0);
        view()->share('merchantsdue', 0);
        view()->share('merchantspaid', 0);
        view()->share('todaymerchantspaid', 0);
        view()->share('deliverycharges', 0);
        view()->share('codecharges', 0);
        view()->share('districts', collect());
        view()->share('socialmedia', collect());
        view()->share('areas', collect());
        view()->share('parceltypes', collect());
        view()->share('allnotelist', collect());
    }

    /**
     * Load actual view data from database
     */    private function loadViewData()
    {
        $ExpenseTypes = ExpenseType::where('status',1)->get();
        view()->share('PublicExpenseTypes',$ExpenseTypes);

        $lastExpNumber = Expense::orderBy('id', 'desc')->pluck('expense_number')->first();

        if ($lastExpNumber === null) {
            // If there are no existing expense numbers, start from 1
            $lastExpNumber = 1;
        } else {
            // Extract the numeric part of the expense number
            preg_match('/\d+$/', $lastExpNumber, $matches);
            $lastNumber = (int)$matches[0];

            // Increment the last expense number
            $lastExpNumber = $lastNumber + 1;
        }

        view()->share('PubliclastExpNUmber', $lastExpNumber);

        $settings = Setting::first();
        view()->share('websettings',$settings);
        $merchants = Merchant::where('status',1)->get();
        view()->share('merchants',$merchants);

        $wcities = City::where('status',1)->orderBy('title','ASC')->get();
        view()->share('wcities',$wcities);
          $wtowns = Town::where('status',1)->get();
        view()->share('wtowns',$wtowns);
        
        $webStatisticsDetails = StatisticsDetails::where('is_active',1)->first();
        if (!$webStatisticsDetails) {
            $webStatisticsDetails = (object) [
                'total_delivery' => 0,
                'total_customers' => 0,
                'total_years' => 0,
                'total_member' => 0
            ];
        }
        view()->share('webStatisticsDetails',$webStatisticsDetails);

        $wchargeTarifs = ChargeTarif::where('status',1)->with('pickupcity','deliverycity')->get();
        view()->share('wchargeTarifs',$wchargeTarifs);

        $contact_info = Contact::find(1);
        view()->share(['contact_info'=>$contact_info]);

        $merchantNotice = Disclamer::find(1);
        view()->share(['merchantNotice'=>$merchantNotice]);

        $agentNotice = Disclamer::find(2);
        view()->share(['agentNotice'=>$agentNotice]);

        $whitelogo = Logo::where('type',1)->limit(1)->get();
        view()->share('whitelogo',$whitelogo); 
        
        $darklogo = Logo::where('type',2)->limit(1)->get();
        view()->share('darklogo',$darklogo); 

        $faveicon = Logo::where('type',3)->limit(1)->get();
        view()->share('faveicon',$faveicon); 

        $services = Service::where('status',1)->get();
        view()->share('services',$services); 

        $newparcel = Parcel::where('status',0)
        ->orderBy('id','DESC')
        ->get();
        view()->share('newparcel',$newparcel); 

        $processingparcel = Parcel::where('status',1)
        ->orderBy('id','DESC')
        ->get();
        view()->share('processingparcel',$processingparcel);

        $onthewayparcel = Parcel::where('status',2)
        ->orderBy('id','DESC')
        ->get();
        view()->share('onthewayparcel',$onthewayparcel);

        $deliverdparcel = Parcel::where('status',3)
        ->orderBy('id','DESC')
        ->get();
        view()->share('deliverdparcel',$deliverdparcel);

        $cancelledparcel = Parcel::where('status',4)
        ->orderBy('id','DESC')
        ->get();
        view()->share('cancelledparcel',$cancelledparcel);

        $returnprocessing = Parcel::where('status',5)
        ->orderBy('id','DESC')
        ->get();
        view()->share('returnprocessing',$returnprocessing);

        $returnparcel = Parcel::where('status',6)
        ->orderBy('id','DESC')
        ->get();
        view()->share('returnparcel',$returnparcel);

        $agents = Agent::where(['status'=>1])
        ->orderBy('id','DESC')
        ->get();
        view()->share('agents',$agents);
        
        $deliverymen = Deliveryman::where(['status'=>1])
        ->orderBy('id','ASC')
        ->get();
        view()->share('deliverymen',$deliverymen);
        
        $newpickup = Pickup::where('status',0)
        ->orderBy('id','DESC')
        ->get();
         view()->share('newpickup',$newpickup);

        
        
        $pendingpickup = Pickup::where('status',1)
        ->orderBy('id','DESC')
        ->get();
        view()->share('pendingpickup',$pendingpickup);
        

        $acceptedpickup = Pickup::where('status',2)
        ->orderBy('id','DESC')
        ->get();
        view()->share('acceptedpickup',$acceptedpickup);

        $cancelledpickup = Pickup::where('status',3)
        ->orderBy('id','DESC')
        ->get();
        view()->share('cancelledpickup',$cancelledpickup);

        $deliverycharge = Deliverycharge::where('status',1)
        ->get();
        view()->share('deliverycharge',$deliverycharge);

        $totalamounts=Parcel::sum('merchantAmount');
        view()->share('totalamounts',$totalamounts);

        // all merchant Due
        $merchantsdue = Parcel::whereIn('status', [4, 6])
        ->sum('merchantDue');

        view()->share('merchantsdue',$merchantsdue);        $merchantspaid=Parcel::sum('merchantPaid');
        view()->share('merchantspaid',$merchantspaid);
       $todaymerchantspaid=Parcel::where('merchantpayStatus',1)->whereDate('updated_at', Carbon::today())->sum('merchantPaid');
        view()->share('todaymerchantspaid',$todaymerchantspaid);

        $deliverycharges=Parcel::sum('deliveryCharge');
        view()->share('deliverycharges',$deliverycharges);

        $codecharges=Parcel::sum('codCharge');
        view()->share('codecharges',$codecharges);

       $districts= District::where('status',1)->orderBy('id','ASC')->get();
        view()->share('districts',$districts);
        
       $socialmedia= Socialmedia::where('status',1)->orderBy('id','ASC')->get();
        view()->share('socialmedia',$socialmedia);

       $areas = Nearestzone::where('status',1)->get();
        view()->share('areas',$areas);

        $parceltypes = Parceltype::orderBy('sl', 'ASC')->get();
        view()->share('parceltypes',$parceltypes);
        
        $allnotelist = Note::get();
        view()->share('allnotelist',$allnotelist);

    }
}
