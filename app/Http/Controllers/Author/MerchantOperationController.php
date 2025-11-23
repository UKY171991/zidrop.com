<?php

namespace App\Http\Controllers\Author;

use App\Deliverycharge;
use App\Disclamer;
use App\Http\Controllers\Controller;
use App\Mail\AddMerchantBalanceMail; 
use App\Mail\MerchantSubscriptionEmail;
use App\Mail\SubtractMerchantBalanceMail;
use App\Merchant;
use App\Merchantcharge;
use App\Merchantpayment;
use App\Merchantreturnpayment;
use App\MerchantSubscriptions;
use App\Nearestzone;
use App\Parcel;
use App\History;
use App\SubscriptionsPlan;
use App\RemainTopup;
use App\Topup;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MerchantOperationController extends Controller {
    public function delete(Request $request, $id) {
        $merchant = Merchant::findOrFail($id);
        $parcel = Parcel::where('merchantId',$id)->get();
        foreach($parcel as $item){
            $item->merchantId = null;
            $item->save();
        }
        $merchant->delete();
        Toastr::success('message', 'Merchant  Deleted successfully!');

        return redirect()->back();
    }

    public function notice() {
        $notice = Disclamer::find(1);

        return view('backEnd.merchant.notice', compact('notice'));
    }

    public function noticestore(Request $request) {
        Disclamer::updateOrCreate(
            [
                'id' => 1,
            ], [
                'title' => $request->title,
            ]
        );

        return back();
    }



    public function notice_agent() {
        $notice = Disclamer::find(2);

        return view('backEnd.agent.notice', compact('notice'));
    }

    public function noticestore_agent(Request $request) {
        Disclamer::updateOrCreate(
            [
                'id' => 2,
            ], [
                'title' => $request->title,
            ]
        );

        return back();
    }

    public function topuphistory() {
        $topup = Topup::with('merchant')->orderBy('id', 'desc')->simplePaginate(200);
        $merchants = Merchant::where('status', 1)
            ->get();
        $usedtopup = RemainTopup::with('parcel')->orderBy('id', 'desc')->get();
        return view('backEnd.merchant.topup-history', compact('topup', 'merchants', 'usedtopup'));
    }

    public function addManualBalance(Request $request)
    {
        DB::beginTransaction();
        try {
            $AmountValue = remove_commas($request->amount);

            $topup = Topup::create([
                'merchant_id' => $request->merchant,
                'email'       => $request->email,
                'amount'      =>  $AmountValue,
                'reference'   => $request->reference,
                'status'      => 'success',
                'channel'     => 'manual',
                'currency'    => 'NGN',
                'mobile'      => $request->mobile,
            ]);

            $merchant          = Merchant::find($request->merchant);
            $merchant->balance = $merchant->balance + ( $AmountValue);
            $merchant->save();

            Mail::to([$request->email])->send(new AddMerchantBalanceMail($merchant,$topup));

        } catch (\Exception $exception) {
            DB::rollback();
            Toastr::error('error', $exception->getMessage());
            return redirect()->back();
        }
        DB::commit();
        Toastr::success('success', 'Balance Added successfully!');
        return redirect()->back();
    }

    public function subtractManualBalance(Request $request)
    {
        DB::beginTransaction();
        try {
            $AmountValue = remove_commas($request->amount);

            $topup = RemainTopup::create([
                'merchant_id'       => $request->merchant,
                'parcel_id'         => 0,
                'parcel_status'     => 1,
                'amount'            => $AmountValue,
                'type'              => 'manual',
                'reference'         => $request->reference,
            ]);

            $merchant          = Merchant::find($request->merchant);
            $merchant->balance = $merchant->balance - ($AmountValue);
            $merchant->save();

            Mail::to([$merchant->emailAddress])->send(new SubtractMerchantBalanceMail($merchant,$topup));

        } catch (\Exception $exception) {
            DB::rollback();
            Toastr::error('error', $exception->getMessage());
            return redirect()->back();
        }
        DB::commit();
        Toastr::success('success', 'Balance Subtract successfully!');
        return redirect()->back();
    }

    public function manage() {
        $merchants = Merchant::orderBy('id', 'ASC')
            ->get();

        //     $parceltype = Parceltype::where('slug',$request->slug)->first();

        $show_data = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->join('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')

            ->orderBy('id', 'DESC')
            ->select('parcels.*', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
            ->get();

        return view('backEnd.merchant.manage', compact('merchants', 'show_data'));
    }

    public function subsplanactivation(Request $request, $id) {
        $merchant_id = $id;
        
        // Get Package 
            $SubsPlan = SubscriptionsPlan::findOrFail($request->subs_plan_id);
            // Get Merchant
            $Merchant = Merchant::findOrFail($merchant_id);
            
            // Remove manual permission based on subscription plan
            switch ($request->subs_plan_id) {
                case 1: // Starter
                    $Merchant->cod_cal_permission = 0;
                    $Merchant->ins_cal_permission = 1;
                    $Merchant->ins_cal_permission = 1;
                    break;
                    
                case 2: // Premium
                    $Merchant->cod_cal_permission = 0;
                    $Merchant->ins_cal_permission = 0;
                    break;
            
                default:
                    // Optionally reset or skip updates for unrecognized plan
                    break;
            }
            
            $Merchant->update();
            
            // find old activate package and delete it
            MerchantSubscriptions::where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0,
                    'auto_renew' => 0,
                    'auto_expired' => 0,
                ]);
                
                // Assign Subscription To merchant
                $MerchantSubscriptions = new MerchantSubscriptions();
                $MerchantSubscriptions->subs_pkg_id   = $SubsPlan->id;
                $MerchantSubscriptions->merchant_id   = $Merchant->id;
                $MerchantSubscriptions->assign_time   = now();
                $MerchantSubscriptions->expired_time  = '2099-12-31 23:59:59';
                $MerchantSubscriptions->is_active     = 1;
                $MerchantSubscriptions->auto_renew    = 1;
                $MerchantSubscriptions->auto_expired    = 0;
                $MerchantSubscriptions->remarks       = 'subs';
                $MerchantSubscriptions->assign_by     = 'admin';
                $MerchantSubscriptions->assign_by_id  =  1;
                $MerchantSubscriptions->save();
                
                
                // History
                $history            = new History();
                $history->name      = "Subscription Activated";
                $history->done_by   = "admin";
                $history->status    = 'subscription_activated';
                $history->note      = "Activated subscription for merchant ID: {$Merchant->id}";
                $history->date      =  now();
                $history->save();
                
             

                // dd(env('MAIL_ADMIN_ADDRESS'));
                // Send Email Notifications
                try {
                \Mail::to($Merchant->emailAddress)->send(new MerchantSubscriptionEmail($Merchant, $SubsPlan, $history, 'merchant', $MerchantSubscriptions));
                \Mail::to(env('MAIL_ADMIN_ADDRESS'))->send(new MerchantSubscriptionEmail($Merchant, $SubsPlan, $history, 'admin', $MerchantSubscriptions));
            } catch (\Exception $exception) {
                // dd($exception->getMessage()); // ?? shows the actual error message
                Log::warning('Subscription mail failed: ' . $exception->getMessage());
            }

            Toastr::success('success', 'Merchant Subscription Activated successfully!');
            return redirect()->back();
    }
    public function merchantrequest() {
        $merchants = Merchant::where('verifyToken', 0)->orderBy('id', 'DESC')->get();

        return view('backEnd.merchant.merchantrequest', compact('merchants'));
    }

    public function profileedit($id) {
        $merchantInfo = Merchant::find($id);
        $nearestzones = Nearestzone::where('status', 1)->get();
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $id)->where('is_active', 1)->first();
        $allpackage   = Deliverycharge::where(['status' => 1])->with(['merchantcharge' => function ($dcharge) use ($id) {
            $dcharge->where('merchantId', $id);
        },
        ])
            ->get();

         $SubsHistos = MerchantSubscriptions::with('plan')
        ->where('merchant_id', $id)
        ->orderByDesc('created_at')
        ->limit(20)
        ->get()
        ->map(function ($item) {
            $item->formatted_date = \Carbon\Carbon::parse($item->assign_time)->format('d/m/Y');
            $item->formatted_time = \Carbon\Carbon::parse($item->assign_time)->format('j M Y') . ' To ' . \Carbon\Carbon::parse($item->expired_time)->format('j M Y');
            return $item;
        });     

        return view('backEnd.merchant.edit', compact('merchantInfo', 'nearestzones', 'allpackage', 'SubsHistos', 'activeSubPlan'));
    }


     public function disablesubsplan(Request $request, $plan_id, $merchant_id)
    {    
        $subs = MerchantSubscriptions::where('subs_pkg_id', $plan_id)
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->first();
    
        if (!$subs) {
            return redirect()->back()->with('error', 'Active subscription not found.');
        }
    
        $subs->is_active = 0;
        $subs->auto_renew = 0;
        $subs->disable_by = 'admin';
        $subs->disable_by_id = 1;
        $subs->disable_time = now();
        $subs->save();



        // Remove manual permission based on subscription plan
        // Get Merchant
        $Merchant = Merchant::findOrFail($subs->merchant_id );
        switch ($subs->subs_pkg_id ) {
            case 1: // Starter
                $Merchant->cod_cal_permission = 1;
                $Merchant->ins_cal_permission = 1;
                break;
        
            case 2: // Premium
                $Merchant->cod_cal_permission = 1;
                $Merchant->ins_cal_permission = 1;
                break;
        
            default:
                // Optionally reset or skip updates for unrecognized plan
                break;
        }

         // History
                $history            = new History();
                $history->name      = "Subscription Disabled";
                $history->done_by   = "admin";
                $history->status    = 'subscription_disabled';
                $history->note      = "Disabled subscription for merchant ID: {$Merchant->id}";
                $history->date      =  now();
                $history->save();
        
        $Merchant->update();
        
        Toastr::success('Success!', 'Thanks! Your subscription disable successfully');
        return redirect()->back();
    }


    // Merchant Profile Edit
    public function profileUpdate(Request $request) {
        $update_merchant = Merchant::find($request->hidden_id);

        $update_image = $request->file('logo');

          if ($update_image) {
              $file = $request->file('logo');
              $name = $file->getClientOriginalName();
              $uploadPath = 'uploads/merchant/';
              File::delete(public_path() . 'uploads/merchant', $update_merchant->logo);
              $file->move($uploadPath, $name);
              $fileUrl = $uploadPath . $name;
          } else {
              $fileUrl = $update_merchant->logo;
          }
          
        $update_merchant->logo = $fileUrl;
        $update_merchant->companyName      = $request->companyName;
        $update_merchant->phoneNumber      = $request->phoneNumber;
        $update_merchant->emailAddress      = $request->emailAddress;
        $update_merchant->pickLocation     = $request->pickLocation;
        $update_merchant->nearestZone      = $request->nearestZone;
        $update_merchant->pickupPreference = $request->pickupPreference;
        $update_merchant->paymentMethod    = $request->paymentMethod;
        $update_merchant->withdrawal       = $request->withdrawal;
        $update_merchant->nameOfBank       = $request->nameOfBank;
        $update_merchant->beneficiary_bank_code       = $request->beneficiary_bank_code;
        $update_merchant->bankBranch       = $request->bankBranch;
        $update_merchant->bankAcHolder     = $request->bankAcHolder;
        $update_merchant->bankAcNo         = $request->bankAcNo;
        $update_merchant->ins_cal_permission      = $request->ins_cal_permission;
        $update_merchant->cod_cal_permission      = $request->cod_cal_permission;
        $update_merchant->save();
        Toastr::success('message', 'Merchant  info update successfully!');

        return redirect()->back();
    }

    public function chargesetup(Request $request) {
        $findexits = Merchantcharge::where(['merchantId' => $request->merchantId, 'packageId' => $request->packageId])->first();

        if (!$findexits) {
            $mcharge                = new Merchantcharge();
            $mcharge->merchantId    = $request->merchantId;
            $mcharge->packageId     = $request->packageId;
            $mcharge->delivery      = $request->delivery;
            $mcharge->extradelivery = $request->extradelivery;
            $mcharge->cod           = $request->cod;
            $mcharge->codpercent    = $request->codpercent;
            $mcharge->save();
        } else {
            $mucharge                = Merchantcharge::where(['merchantId' => $request->merchantId, 'packageId' => $request->packageId])->first();
            $mucharge->merchantId    = $request->merchantId;
            $mucharge->packageId     = $request->packageId;
            $mucharge->delivery      = $request->delivery;
            $mucharge->extradelivery = $request->extradelivery;
            $mucharge->cod           = $request->cod;
            $mucharge->codpercent    = $request->codpercent;
            $mucharge->save();
        }

        Toastr::success('message', 'Merchant  info update successfully!');

        return redirect()->back();
    }

    public function inactive(Request $request) {
        $inactive_merchant         = Merchant::find($request->hidden_id);
        $inactive_merchant->status = 0;
        $inactive_merchant->save();
        Toastr::success('message', 'Merchant  inactive successfully!');

        return redirect()->back();
    }

    public function active(Request $request) {
        $active_merchant              = Merchant::find($request->hidden_id);
        $active_merchant->status      = 1;
        $active_merchant->verifyToken = 1;
        $active_merchant->save();

        $active_merchant = Merchant::find($request->hidden_id);
        $url             = "http://premium.mdlsms.com/smsapi";
        $data            = [
            "api_key"  => "C20005455f867568bd8c02.20968541",
            "type"     => "text",
            "contacts" => "0$active_merchant->phoneNumber",

            "senderid" => "8809612440738",
            "msg"      => "Dear $active_merchant->companyName\r\n  Successfully boarded your account. Now you can login & enjoy our services.\r\nRegards,\r\n Zuri Express",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        Toastr::success('message', 'Merchant active successfully!');

        return redirect()->back();

    }

    public function view($id) {
        $merchantInfo = Merchant::find($id);

        $totalamount = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.merchantpayStatus', null)
            ->where('parcels.status', 4)
            ->orWhere('parcels.status', 6)
            ->orWhere('parcels.status', 10)
            ->sum('parcels.merchantAmount');

        $totaldue = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->Where('parcels.status', 4)
            ->orWhere('parcels.status', 6)
            ->orWhere('parcels.status', 10)
            ->sum('parcels.merchantDue');

        $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->orderBy('parcels.id', 'DESC')
            ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
            ->get();

        return view('backEnd.merchant.view', compact('merchantInfo', 'parcels'));
    }

    public function paymentinvoice($id) {
        // $merchantInvoice = Merchantpayment::where('merchantId', $id)->get();
    $merchantInvoice =DB::table('merchantpayments')
        ->join('parcels','parcels.id','merchantpayments.parcelId')
        ->selectRaw('DATE(merchantpayments.updated_at) as date, count(merchantpayments.id) as total_parcel,sum(parcels.merchantPaid) as total, merchantpayments.updated_at, merchantpayments.parcelId')
        ->groupBy('merchantpayments.updated_at')
        ->where('merchantpayments.merchantId', $id)
        ->orderBy('updated_at', 'DESC')
        ->get();

        return view('backEnd.merchant.paymentinvoice', compact('merchantInvoice'));
    }

    public function returnpaymentinvoice($id){ 
        $merchantInvoice =DB::table('merchantreturnpayments')
        ->join('parcels','parcels.id','merchantreturnpayments.parcelId')
        ->selectRaw('DATE(merchantreturnpayments.updated_at) as date, count(merchantreturnpayments.id) as total_parcel,sum(parcels.deliveryCharge + parcels.tax + parcels.insurance) as total, merchantreturnpayments.updated_at, merchantreturnpayments.done_by, merchantreturnpayments.parcelId')
        ->groupBy('merchantreturnpayments.updated_at')
        ->where('merchantreturnpayments.merchantId', $id)
        ->orderBy('updated_at', 'DESC')
        ->get();

        return view('backEnd.merchant.returnpaymentinvoice', compact('merchantInvoice'));
    }

    public function inovicedetails(Request $request) {
        $update = $request->update;
        Log::info("update date: ".$update.". MerchantID: ".$request->merchant_id);
        $parcelId = Merchantpayment::where('updated_at', $update)
            ->where('merchantId', $request->merchant_id)
            ->pluck('parcelId')
            ->toArray();
        $parcels   = DB::table('parcels')->whereIn('id', $parcelId)->get();
           
        $merchantInfo = Merchant::find($request->merchant_id);
        return view('backEnd.merchant.inovicedetails', compact('parcels','merchantInfo'));
    }

    public function returninovicedetails(Request $request) {
        $update = $request->update;
        Log::info("update date: ".$update.". MerchantID: ".$request->merchant_id);
        $parcelId = Merchantreturnpayment::where('updated_at', $update)
            ->where('merchantId', $request->merchant_id)
            ->pluck('parcelId')
            ->toArray();
        $parcels = Parcel::whereIn('id', $parcelId)->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->get();

        
        $marchent = Merchant::find($request->merchant_id);
        return view('backEnd.parcel.invoice_return_to_merchat', compact('parcels', 'marchent', 'update'));
        // return view('backEnd.merchant.returninovicedetails', compact('parcels','merchantInfo'));
    }

}
