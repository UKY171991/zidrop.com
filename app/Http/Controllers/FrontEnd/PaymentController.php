<?php

namespace App\Http\Controllers\FrontEnd;

use App\History;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\P2pParcels;
use App\Parcel;
use App\Parcelnote;
use App\PaymentHistory;
use App\RemainTopup;
use App\SubscriptionsPlan;
use App\MerchantSubscriptions;
use App\Topup;
use Mail;
use App\Mail\ParcelCreateEmail;
use App\Mail\MerchantSubscriptionEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function topup()
    {
        $results = DB::table('paymentapis')->where('id', 1)->first();
        $merchant = Merchant::find(Session::get('merchantId'));
        $topup = Topup::where('merchant_id', $merchant->id)->orderBy('id', 'desc')->get();

        $usedtopup = RemainTopup::where('merchant_id', Session::get('merchantId'))
            ->with('parcel')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('frontEnd.layouts.pages.merchant.topup', compact('merchant', 'topup', 'usedtopup','results'));
    }

    public function verifypaymensubs(Request $request, $reference)
    {
            $results = DB::table('paymentapis')->where('id', 1)->first();
            $curl = curl_init();
    
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                // CURLOTP_SSL_VERIFYHOST => 0,
                // CURLOTP_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    // "Authorization: Bearer sk_test_e9074038245937b3bed4f28cd8dd7722ee37e733",
                    "Authorization: Bearer ".$results->secret,
                    "Cache-Control: no-cache",
                ],
            ]);
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
            $new_data = json_decode($response);
            $new_data = [$new_data];
    
            return $new_data;

       
    }
    public function storeSubsPayment(Request $request)
    {
        $paymentResponse = $request->paymentResponse[0]['data'] ?? null;
    
        if (!$paymentResponse) {
            return response()->json(['error' => 'Invalid payment response'], 400);
        }
        DB::beginTransaction();
        
        try {
            // Get Package 
            $SubsPlan = SubscriptionsPlan::findOrFail($request->subs_plan_id);
            // Get Merchant
            $Merchant = Merchant::findOrFail($request->merchant_id);

            // Remove manual permission based on subscription plan
            switch ($request->subs_plan_id) {
                case 1: // Starter
                    $Merchant->cod_cal_permission = 0;
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
            MerchantSubscriptions::where('merchant_id', $request->merchant_id)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0,
                    'auto_renew' => 0,
                ]);
                
                // Assign Subscription To merchant
                $MerchantSubscriptions = new MerchantSubscriptions();
                $MerchantSubscriptions->subs_pkg_id   = $SubsPlan->id;
                $MerchantSubscriptions->merchant_id   = $Merchant->id;
                $MerchantSubscriptions->assign_time   = now();
                $MerchantSubscriptions->expired_time  = now()->addDays($SubsPlan->duration);
                $MerchantSubscriptions->is_active     = 1;
                $MerchantSubscriptions->auto_renew    = 1;
                $MerchantSubscriptions->remarks       = 'subs';
                $MerchantSubscriptions->assign_by     = 'merchant';
                $MerchantSubscriptions->assign_by_id  = $Merchant->id;
                $MerchantSubscriptions->save();
                
                
                // Store payment history
                $history = new PaymentHistory();
                $history->payment_type      = 'subs';
                $history->payment_purpose   = 'subs';
                $history->transactionId     = 'SUBS-' . rand(100000, 999999);
                $history->refference_no     = $request->reference;
                $history->paid_at           = $paymentResponse['paidAt'];
                $history->amount            = $paymentResponse['amount'];
                $history->fees              = $paymentResponse['fees'];
                $history->card_holder_name  = $paymentResponse['authorization']['account_name'] ?? '';
                $history->card_type         = $paymentResponse['authorization']['card_type'] ?? '';
                $history->card_no           = $paymentResponse['authorization']['last4'] ?? '';
                $history->card_expirity     = ($paymentResponse['authorization']['exp_month'] ?? '') . '/' . ($paymentResponse['authorization']['exp_year'] ?? '');
                $history->bank_name         = $paymentResponse['authorization']['bank'] ?? '';
                $history->reference         = 'subs';
                $history->status            = $request->paymentResponse[0]['status'] ?? '';
                $history->channel           = $paymentResponse['authorization']['channel'] ?? '';
                $history->currency          = $paymentResponse['currency'] ?? '';
                $history->metadata          = $paymentResponse['metadata']['referrer'] ?? '';
                $history->user_ip           = $paymentResponse['ip_address'] ?? '';
                $history->transaction_date  = $paymentResponse['transaction_date'] ?? now();
                $history->relational_type   = 'merchant_subscription';
                $history->relational_id     = $MerchantSubscriptions->id;
                $history->save();
                
                DB::commit(); // ✅ Commit everything if successful
                
                // Send Email Notifications
                try {
                Mail::to($Merchant->emailAddress)->send(new MerchantSubscriptionEmail($Merchant, $SubsPlan, $history, 'merchant', $MerchantSubscriptions));
                Mail::to(env('MAIL_ADMIN_ADDRESS'))->send(new MerchantSubscriptionEmail($Merchant, $SubsPlan, $history, 'admin', $MerchantSubscriptions));
            } catch (\Exception $exception) {
                // dd($exception->getMessage()); // 👈 shows the actual error message
                Log::warning('Subscription mail failed: ' . $exception->getMessage());
            }
            // dd($Merchant);
    
            return response()->json(['success' => true], 200);
    
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Rollback everything if any error occurs
             dd($e->getMessage()); 
            Log::error('Subscription transaction failed: ' . $e->getMessage());
            return response()->json(['error' => 'Subscription failed. Please try again.'], 500);
        }
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
        $subs->disable_by = 'merchant';
        $subs->disable_by_id = $merchant_id;
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
        
        $Merchant->update();
        
        Toastr::success('Success!', 'Thanks! Your subscription disable successfully');
        return redirect()->back();
    }


    public function verifypayment($reference)
    {
        $results = DB::table('paymentapis')->where('id', 1)->first();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            // CURLOTP_SSL_VERIFYHOST => 0,
            // CURLOTP_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                //"Authorization: Bearer sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7",
                "Authorization: Bearer ".$results->secret,
                "Cache-Control: no-cache",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $new_data = json_decode($response);
        $new_data = [$new_data];

        return $new_data;
    }

    public function storePayment(Request $request)
    {
        $topup = Topup::create([
            'merchant_id' => Session::get('merchantId'),
            'email' => $request->email,
            'amount' => $request->amount / 100,
            'reference' => $request->reference,
            'status' => $request->status,
            'channel' => $request->channel,
            'currency' => $request->currency,
            'mobile' => $request->mobile,
        ]);

        $merchant = Merchant::find(Session::get('merchantId'));
        $merchant->balance = $merchant->balance + ($request->amount / 100);
        $merchant->save();

        $count = Topup::where('merchant_id', Session::get('merchantId'))->count();

        return response()->json(['status' => true, 'top' => $topup, 'count' => $count]);
    }

    public function ppverifypayment($reference) 
    {
        $results = DB::table('paymentapis')->where('id', 1)->first();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            // CURLOTP_SSL_VERIFYHOST => 0,
            // CURLOTP_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                // pk_live_28c1a12799b3241d151449ceb00cd5661beec03e
                // sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7

                // pk_test_9e185aac0936fd9313529f6471cdc37873adc730
                // sk_test_e9074038245937b3bed4f28cd8dd7722ee37e733
                //"Authorization: Bearer sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7",
                "Authorization: Bearer sk_test_e9074038245937b3bed4f28cd8dd7722ee37e733",
                // "Authorization: Bearer ".$results->secret,
                "Cache-Control: no-cache",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $new_data = json_decode($response);
        $new_data = [$new_data];

        return $new_data;
    }
    public function agent_ppverifypayment($reference)
    {
        $results = DB::table('paymentapis')->where('id', 1)->first();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            // CURLOTP_SSL_VERIFYHOST => 0,
            // CURLOTP_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                // pk_live_28c1a12799b3241d151449ceb00cd5661beec03e
                // sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7

                // pk_test_9e185aac0936fd9313529f6471cdc37873adc730
                // sk_test_e9074038245937b3bed4f28cd8dd7722ee37e733
                //"Authorization: Bearer sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7",
                //"Authorization: Bearer sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7",
                "Authorization: Bearer ".$results->secret,
                "Cache-Control: no-cache",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $new_data = json_decode($response);
        $new_data = [$new_data];

        return $new_data;
    }


    public function p2psubmit(Request $request)
    {
        try {
            // Step 1: Fetch charge and town data together to avoid multiple queries.
            $charge = \App\ChargeTarif::where([
                ['pickup_cities_id', $request->sender_pickupcity],
                ['delivery_cities_id', $request->recipient_pickupcity]
            ])->first();
            

            if (!$charge) {
                return response()->json(['status' => false, 'message' => 'Invalid charge details'], 400);
            }

            $town = \App\Town::where([
                ['id', $request->recipient_pickuptown],
                ['cities_id', $request->recipient_pickupcity]
            ])->first();

            if (!$town) {
                return response()->json(['status' => false, 'message' => 'Invalid town details'], 400);
            }

            // Step 2: Delivery charge and weight calculation
            $weight = $request->parcel_weight ?? 1;
            $extraWeight = max(0, $weight - 1);
            $deliveryCharge = $charge->deliverycharge + $town->towncharge + ($extraWeight * $charge->extradeliverycharge);

            // Step 3: Tax and insurance calculations
            $tax = round(($deliveryCharge * $charge->tax) / 100, 2);
            $insurance = round($request->product_value * ($charge->insurance / 100), 2);
            $codCharge = ($request->cod && $charge) ? round(($request->cod * $charge->codcharge) / 100, 2) : 0;

            // Step 4: Store parcel details
            $store_parcel = new Parcel();
            $store_parcel->fill([
                'percelType' => $request->parcel_type,
                'cod' => $request->cod ?? 0,
                'package_value' => $request->product_value ?? 0,
                'tax' => $tax,
                'insurance' => $insurance,
                'recipientName' => $request->recivier_name,
                'recipientAddress' => $request->recivier_address,
                'recipientPhone' => $request->recivier_mobile,
                'productWeight' => $weight,
                'productName' => $request->item_name,
                'productQty' => $request->number_of_item,
                'productColor' => $request->color,
                'trackingCode' => 'ZD' . mt_rand(1111111111, 9999999999),
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'deliveryCharge' => $deliveryCharge,
                'codCharge' => $codCharge,
                'reciveZone' => $request->reciveZone,
                'merchantAmount' => 0,
                'merchantDue' => 0,
                'payment_option' => 1,
                'orderType' => $request->package ?? 0,
                'codType' => 1,
                'status' => 1,
                'parcel_source' => 'p2p',
                'pickup_cities_id' => $request->sender_pickupcity,
                'delivery_cities_id' => $request->recipient_pickupcity,
                'pickup_town_id' => $request->sender_pickuptown,
                'delivery_town_id' => $request->recipient_pickuptown,
            ]);
            $store_parcel->save();

            // Step 5: Store history and note
            $history = History::create([
                'name' => "Customer: " . $store_parcel->recipientName . "<br><b>(Created By: P2P)</b>",
                'parcel_id' => $store_parcel->id,
                'done_by' => 'P2P',
                'status' => 'Parcel Created By P2P',
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'date' => now(),
            ]);

            Parcelnote::create([
                'parcelId' => $store_parcel->id,
                'note' => 'Pending Pickup',
            ]);

            // Step 6: Payment history creation
            $paymentResponse = $request->paymentResponse[0]['data'] ?? null;
            if ($paymentResponse) {
                PaymentHistory::create([
                    'payment_type' => 'P2P',
                    'payment_purpose' => 'P2P',
                    'transactionId' => 'P2P-' . rand(100000, 999999),
                    'refference_no' => $request->reference,
                    'paid_at' => $paymentResponse['paidAt'],
                    'amount' => $paymentResponse['amount'],
                    'fees' => $paymentResponse['fees'],
                    'card_holder_name' => $paymentResponse['authorization']['account_name'] ?? '',
                    'card_type' => $paymentResponse['authorization']['card_type'] ?? '',
                    'card_auth_code' => $paymentResponse['authorization']['authorization_code'] ?? '',
                    'card_bin' => $paymentResponse['authorization']['bin'] ?? '',
                    'card_last4' => $paymentResponse['authorization']['last4'] ?? '',
                    'card_no' => $paymentResponse['authorization']['last4'] ?? '',
                    'card_expirity' => ($paymentResponse['authorization']['exp_month'] ?? '') . '/' . ($paymentResponse['authorization']['exp_year'] ?? ''),
                    'bank_name' => $paymentResponse['authorization']['bank'] ?? '',
                    'reference' => 'P2P',
                    'status' => $request->paymentResponse[0]['status'] ?? '',
                    'channel' => $paymentResponse['authorization']['channel'] ?? '',
                    'currency' => $paymentResponse['currency'] ?? '',
                    'metadata' => $paymentResponse['metadata']['referrer'] ?? '',
                    'user_ip' => $paymentResponse['ip_address'] ?? '',
                    'transaction_date' => $paymentResponse['transaction_date'] ?? now(),
                    'relational_type' => 'P2P',
                    'relational_id' => $store_parcel->id,
                ]);
            }

           // Step 7: Store P2pParcels
            $P2pParcels = P2pParcels::create([
                'parcel_id' => $store_parcel->id,
                'sender_name' => $request->sender_name,
                'sender_mobile' => $request->sender_mobile,
                'sender_email' => $request->sender_email,
                'sender_pickupcity' => $request->sender_pickupcity,
                'sender_pickuptown' => $request->sender_pickuptown,
                'sender_address' => $request->sender_address,
                'recivier_name' => $request->recivier_name,
                'recivier_mobile' => $request->recivier_mobile,
                'recipient_pickupcity' => $request->recipient_pickupcity,
                'recipient_pickuptown' => $request->recipient_pickuptown,
                'recivier_address' => $request->recivier_address,
                'terms_condition' => $request->terms_and_condition,
                'payment_id' => $paymentResponse ? $paymentResponse['id'] : null,
            ]);

            // Step 8: Send email notification
            try {
                if (!empty($P2pParcels)) {
                    Mail::to($P2pParcels->sender_email)->send(new ParcelCreateEmail($P2pParcels, $store_parcel, $history));
                }
            } catch (\Exception $exception) {
                Log::info('Parcel Create mail error: ' . $exception->getMessage());
            }

            session()->flash('open_url', url('/web/parcel/invoice/' . $store_parcel->id));
            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            Log::error('Error in P2P Parcel creation: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }




    public function agent_p2psubmit(Request $request)
    {
      
        $agentId = Session::get('agentId');
        
        try {
            // Step 1: Fetch charge and town data together to avoid multiple queries.
            $charge = \App\ChargeTarif::where([
                ['pickup_cities_id', $request->sender_pickupcity],
                ['delivery_cities_id', $request->recipient_pickupcity]
            ])->first();

            if (!$charge) {
                return response()->json(['status' => false, 'message' => 'Invalid charge details'], 400);
            }

            $town = \App\Town::where([
                ['id', $request->recipient_pickuptown],
                ['cities_id', $request->recipient_pickupcity]
            ])->first();

            if (!$town) {
                return response()->json(['status' => false, 'message' => 'Invalid town details'], 400);
            }

            // Step 2: Delivery charge and weight calculation
            $weight = $request->parcel_weight ?? 1;
            $extraWeight = max(0, $weight - 1);
            $deliveryCharge = $charge->deliverycharge + $town->towncharge + ($extraWeight * $charge->extradeliverycharge);

            // Step 3: Tax and insurance calculations
            $tax = round(($deliveryCharge * $charge->tax) / 100, 2);
            $insurance = round($request->product_value * ($charge->insurance / 100), 2);
            $codCharge = ($request->cod && $charge) ? round(($request->cod * $charge->codcharge) / 100, 2) : 0;

            // Step 4: Store parcel details
            $store_parcel = new Parcel();
            $store_parcel->fill([
                'percelType' => $request->parcel_type,
                'cod' => $request->cod ?? 0,
                'package_value' => $request->product_value ?? 0,
                'tax' => $tax,
                'insurance' => $insurance,
                'recipientName' => $request->recivier_name,
                'recipientAddress' => $request->recivier_address,
                'recipientPhone' => $request->recivier_mobile,
                'productWeight' => $weight,
                'productName' => $request->item_name,
                'productQty' => $request->number_of_item,
                'productColor' => $request->color,
                'trackingCode' => 'ZD' . mt_rand(1111111111, 9999999999),
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'deliveryCharge' => $deliveryCharge,
                'codCharge' => $codCharge,
                'reciveZone' => $request->reciveZone,
                'merchantAmount' => 0,
                'merchantDue' => 0,
                'payment_option' => 1,
                'orderType' => $request->package ?? 0,
                'codType' => 1,
                'status' => 1,
                'parcel_source' => 'p2p',
                'agentId' => $agentId,
                'pickup_cities_id' => $request->sender_pickupcity,
                'delivery_cities_id' => $request->recipient_pickupcity,
                'pickup_town_id' => $request->sender_pickuptown,
                'delivery_town_id' => $request->recipient_pickuptown,
                'discounted_value' => $request->discounted_value,
                'p2p_payment_option' => $request->payment_option,
            ]);
            // Conditionally set 'agentAmount'
            if ($request->payment_option == 'pay_later') {
                $data['agentAmount'] = $request->product_value;
            }
            $store_parcel->save();

            // Step 5: Store history and note
            $history = History::create([
                'name' => "Customer: " . $store_parcel->recipientName . "<br><b>(Created By: P2P)</b>",
                'parcel_id' => $store_parcel->id,
                'done_by' => 'P2P',
                'status' => 'Parcel Created By P2P',
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'date' => now(),
            ]);

            Parcelnote::create([
                'parcelId' => $store_parcel->id,
                'note' => 'Pending Pickup',
            ]);

            // Step 6: Payment history creation
            $paymentResponse = $request->paymentResponse[0]['data'] ?? null;
            if ($paymentResponse) {
                PaymentHistory::create([
                    'payment_type' => 'P2P',
                    'payment_purpose' => 'P2P',
                    'transactionId' => 'P2P-' . rand(100000, 999999),
                    'refference_no' => $request->reference,
                    'paid_at' => $paymentResponse['paidAt'],
                    'amount' => $paymentResponse['amount'],
                    'fees' => $paymentResponse['fees'],
                    'card_holder_name' => $paymentResponse['authorization']['account_name'] ?? '',
                    'card_type' => $paymentResponse['authorization']['card_type'] ?? '',
                    'card_auth_code' => $paymentResponse['authorization']['authorization_code'] ?? '',
                    'card_bin' => $paymentResponse['authorization']['bin'] ?? '',
                    'card_last4' => $paymentResponse['authorization']['last4'] ?? '',
                    'card_no' => $paymentResponse['authorization']['last4'] ?? '',
                    'card_expirity' => ($paymentResponse['authorization']['exp_month'] ?? '') . '/' . ($paymentResponse['authorization']['exp_year'] ?? ''),
                    'bank_name' => $paymentResponse['authorization']['bank'] ?? '',
                    'reference' => 'P2P',
                    'status' => $request->paymentResponse[0]['status'] ?? '',
                    'channel' => $paymentResponse['authorization']['channel'] ?? '',
                    'currency' => $paymentResponse['currency'] ?? '',
                    'metadata' => $paymentResponse['metadata']['referrer'] ?? '',
                    'user_ip' => $paymentResponse['ip_address'] ?? '',
                    'transaction_date' => $paymentResponse['transaction_date'] ?? now(),
                    'relational_type' => 'P2P',
                    'relational_id' => $store_parcel->id,
                ]);
            }

           // Step 7: Store P2pParcels
            $P2pParcels = P2pParcels::create([
                'parcel_id' => $store_parcel->id,
                'sender_name' => $request->sender_name,
                'sender_mobile' => $request->sender_mobile,
                'sender_email' => $request->sender_email,
                'sender_pickupcity' => $request->sender_pickupcity,
                'sender_pickuptown' => $request->sender_pickuptown,
                'sender_address' => $request->sender_address,
                'recivier_name' => $request->recivier_name,
                'recivier_mobile' => $request->recivier_mobile,
                'recipient_pickupcity' => $request->recipient_pickupcity,
                'recipient_pickuptown' => $request->recipient_pickuptown,
                'recivier_address' => $request->recivier_address,
                'terms_condition' => 1,
                'payment_id' => $paymentResponse ? $paymentResponse['id'] : null,
                'payment_option' => $request->payment_option,
            ]);

            // Step 8: Send email notification
            try {
                if (!empty($P2pParcels)) {
                    Mail::to($P2pParcels->sender_email)->send(new ParcelCreateEmail($P2pParcels, $store_parcel, $history));
                }
            } catch (\Exception $exception) {
                Log::info('Parcel Create mail error: ' . $exception->getMessage());
            }

            session()->flash('open_url', url('/agent/parcel/invoice/' . $store_parcel->id));
            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            Log::error('Error in P2P Parcel creation: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }
    public function p2p_store_cash_submit(Request $request)
    {
        $agentId = Session::get('agentId');
        // dd($request->all());
        
        try {
            // Step 1: Fetch charge and town data together to avoid multiple queries.
            $charge = \App\ChargeTarif::where([
                ['pickup_cities_id', $request->sender_pickupcity],
                ['delivery_cities_id', $request->recipient_pickupcity]
            ])->first();

            if (!$charge) {
                return response()->json(['status' => false, 'message' => 'Invalid charge details'], 400);
            }

            $town = \App\Town::where([
                ['id', $request->recipient_pickuptown],
                ['cities_id', $request->recipient_pickupcity]
            ])->first();

            if (!$town) {
                return response()->json(['status' => false, 'message' => 'Invalid town details'], 400);
            }

            // Step 2: Delivery charge and weight calculation
            $weight = $request->parcel_weight ?? 1;
            $extraWeight = max(0, $weight - 1);
            $deliveryCharge = $charge->deliverycharge + $town->towncharge + ($extraWeight * $charge->extradeliverycharge);

            // Step 3: Tax and insurance calculations
            $tax = round(($deliveryCharge * $charge->tax) / 100, 2);
            $insurance = round($request->product_value * ($charge->insurance / 100), 2);
            $codCharge = ($request->cod && $charge) ? round(($request->cod * $charge->codcharge) / 100, 2) : 0;
            
            $totalCharge = $deliveryCharge + $tax + $insurance ;
            
            // Payment Option
            if($request->payment_option == 'cash'){
                $CashDuePayment = 0;
                $ParcelPaymentOption = 1; //1 = paid, 1= pay on delivery
            }elseif($request->payment_option == 'pay_later'){
                $CashDuePayment = $totalCharge; 
                $ParcelPaymentOption = 2; //1 = paid, 1= pay on delivery     
            }else{
                $CashDuePayment = 0;
                $ParcelPaymentOption = 1; //1 = paid, 1= pay on delivery
            }
            
            // Step 4: Store parcel details
            $store_parcel = new Parcel();
            // dd($request->payment_option);
            $store_parcel->fill([
                'percelType' => $request->parcel_type,
                'cod' => $request->cod ?? 0,
                'package_value' => $request->product_value ?? 0,
                'tax' => $tax,
                'insurance' => $insurance,
                'recipientName' => $request->recivier_name,
                'recipientAddress' => $request->recivier_address,
                'recipientPhone' => $request->recivier_mobile,
                'productWeight' => $weight,
                'productName' => $request->item_name,
                'productQty' => $request->number_of_item,
                'productColor' => $request->color,
                'trackingCode' => 'ZD' . mt_rand(1111111111, 9999999999),
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'deliveryCharge' => $deliveryCharge,
                'codCharge' => $codCharge,
                'reciveZone' => $request->reciveZone,
                'merchantAmount' => 0,
                'merchantDue' => 0,
                'payment_option' => $ParcelPaymentOption,
                'orderType' => $request->package ?? 0,
                'codType' => 1,
                'status' => 1,
                'parcel_source' => 'p2p',
                'p2p_payment_option' => $request->payment_option,
                'agentId' => $agentId,
                'pickup_cities_id' => $request->sender_pickupcity,
                'delivery_cities_id' => $request->recipient_pickupcity,
                'pickup_town_id' => $request->sender_pickuptown,
                'delivery_town_id' => $request->recipient_pickuptown,
                'discounted_value' => $request->discounted_value,
            ]);
            if ($request->payment_option == 'pay_later') {
                $store_parcel['agentAmount'] = $request->product_value;
            }
        //  dd($store_parcel);

            $store_parcel->save();
           

            // Step 5: Store history and note
            $history = History::create([
                'name' => "Customer: " . $store_parcel->recipientName . "<br><b>(Created By: P2P)</b>",
                'parcel_id' => $store_parcel->id,
                'done_by' => 'P2P',
                'status' => 'Parcel Created By P2P',
                'note' => $request->parcel_contain ?? 'Pending Pickup',
                'date' => now(),
            ]);
            
            Parcelnote::create([
                'parcelId' => $store_parcel->id,
                'note' => 'Pending Pickup',
            ]);

          
           // Step 7: Store P2pParcels
            $P2pParcels = P2pParcels::create([
                'parcel_id' => $store_parcel->id,
                'sender_name' => $request->sender_name,
                'sender_mobile' => $request->sender_mobile,
                'sender_email' => $request->sender_email,
                'sender_pickupcity' => $request->sender_pickupcity,
                'sender_pickuptown' => $request->sender_pickuptown,
                'sender_address' => $request->sender_address,
                'recivier_name' => $request->recivier_name,
                'recivier_mobile' => $request->recivier_mobile,
                'recipient_pickupcity' => $request->recipient_pickupcity,
                'recipient_pickuptown' => $request->recipient_pickuptown,
                'recivier_address' => $request->recivier_address,
                'terms_condition' => 1,
                'payment_option' => $request->payment_option,
            ]);
           
            // Step 8: Send email notification
            try {
                if (!empty($P2pParcels)) {
                    Mail::to($P2pParcels->sender_email)->send(new ParcelCreateEmail($P2pParcels, $store_parcel, $history));
                }
            } catch (\Exception $exception) {
                Log::info('Parcel Create mail error: ' . $exception->getMessage());
            }

            session()->flash('open_url', url('/agent/p2p-parcel/invoice/' . $store_parcel->id));
            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            Log::error('Error in P2P Parcel creation: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }
}
