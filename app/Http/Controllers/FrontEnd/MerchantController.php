<?php
namespace App\Http\Controllers\FrontEnd;

use App\Codcharge;
use App\Deliverycharge;
use App\Deliveryman;
use App\Disclamer;
use App\Exports\ParcelExport;
use App\History;
use App\Http\Controllers\Controller;
use App\Imports\ParcelImport;
use App\Mail\MerchantRegisterAlertMailable;
use App\Mail\MerchantRegistrationEmail;
use App\Mail\MerchantResetPasswordEmail;
use App\Mail\NewPickupRequestEmail;
use App\Mail\OtpMail;
use App\Merchant;
use App\Merchantpayment;
use App\Merchantreturnpayment;
use App\Nearestzone;
use App\Notice;
use App\Parcel;
use App\Parcelnote;
use App\MerchantSubscriptions;
use App\Parceltype;
use App\Pickup;
use App\RemainTopup;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MerchantController extends Controller
{
    

    public function registerpage()
    {
        return view('frontEnd.layouts.pages.register');
    }

    public function register_otp(request $request)
    {
        $otp = rand(100000, 999999);
        // Validate email
        $request->validate([
            'email' => 'required|email',
        ]);
        Mail::to($request->email)->send(new OtpMail($otp));

        // Store OTP in session (or you can save it to a database)
        Session::put('otp', $otp);
        Session::put('email_otp', $request->email);

        return response()->json(['message' => 'OTP sent successfully', 'email' => $request->email]);
        exit();
    }

    public function verifyOtp(Request $request)
    {
        // Validate input OTP
        $request->validate([
            'otp'   => 'required|numeric',
            'email' => 'required',
        ]);

        // Retrieve OTP from session (or database)
        $storedOtp = Session::get('otp');

        $storedEmail = Session::get('email_otp');

        if ($request->otp == $storedOtp && $storedEmail == $request->email) {
            return response()->json(['message' => 'OTP verified successfully.', 'status' => 'true']);
        } else {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'companyName'  => 'required',
            'firstName'    => 'required',
            'phoneNumber'  => 'required',
            'emailAddress' => 'unique:merchants',
            'password'     => 'required|same:confirmed',
            'confirmed'    => 'required',
        ]);

        $storedOtp = Session::get('otp');
        if ($storedOtp != $request->otp) {
            Toastr::error('message', 'Opps! your otp is not  verifiyed');

            return redirect()->back();
        }
        //return $request->all();
        $marchentCheck = Merchant::where('phoneNumber', $request->phoneNumber)->where('emailAddress', $request->emailAddress)->first();

        if ($marchentCheck) {
            Toastr::error('message', 'Opps! your credential already used');

            return redirect()->back();
        } else {
            $store_data               = new Merchant();
            $verifyToken              = rand(111111, 999999);
            $store_data->companyName  = $request->companyName;
            $store_data->firstName    = $request->firstName;
            $store_data->lastName     = $request->lastName;
            $store_data->phoneNumber  = $request->phoneNumber;
            $store_data->emailAddress = $request->emailAddress;
            $store_data->agree        = $request->agree;
            $store_data->password     = bcrypt(request('password'));
            $store_data->verifyToken  = 1;
            $store_data->status       = 0;
            $store_data->save();

            /*$url  = "https://sms.solutionsclan.com/api/sms/send";
            $data = [
            "apiKey"         => "A00003133467cc6-219a-4dfd-882e-0bcf8836ebc3",
            "contactNumbers" => $request->phoneNumber,
            "senderId"       => "8809612440632",
            "textBody"       => "Dear $request->companyName\r\nSuccessfully boarded your account. Your verified token is    $verifyToken .\r\nRegards,\r\n Zuri Express",
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            echo "$response";
            curl_close($ch);

            Session::put('phoneverify', $store_data->phoneNumber);*/

            try {
                Mail::to($store_data->emailAddress)->send(new MerchantRegistrationEmail($store_data));
            } catch (\Exception $exception) {
                Log::info('Merchant-Register mail error: ' . $exception->getMessage());
            }

            // Send an email to Admin to notify about the new merchant registration
            $receiverEmail = 'e-tailing@zidrop.com';
            try {
                Mail::to($receiverEmail)->send(new MerchantRegisterAlertMailable($store_data));
            } catch (\Exception $exception) {
                Log::info('Merchant-Register-Alert mail error: ' . $exception->getMessage());
            }
            Toastr::success('message', 'Thank you for signing up with us! A dedicated representative will be reaching out to you shortly.');

//          return redirect('merchant/phone-verify');
            return redirect('merchant/login');
        }

    }

    public function subscriptions()
    {
        return view('frontEnd.layouts.pages.merchant.subscriptions');
    }

    public function subscriptions_activate(Request $request)
    {
        // Step 1: Get selected plan
        $chosenPlan = (int) $request->subs_plan_id;

        // Step 2: Match plan to Paystack code
        $planCode = match ($chosenPlan) {
            1 => 'PLN_rcryul0lzdnk2of', // Business Starter
            2 => 'PLN_t2jhfilin87mu7v', // Business Premium
            default => null
        };

        // Step 3: If no valid plan, abort
        if (! $planCode) {
            return redirect()->back()->with('error', 'Invalid subscription plan selected.');
        }


        // Step 4: Send request to Paystack
        $response = Http::withToken('sk_live_4595a23b03386276bd33a7d4f48db9db5b39faf7')
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.paystack.co/subscription/enable', [
                'code'  => $planCode,
                'token' => 'd7gofp6yppn3qz7', // This should probably come from the user/session
            ]);

        // Step 5: Check for failure
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Failed to enable subscription: ' . $response->body());
        }

        // Step 6: Optional success response
        return view('frontEnd.layouts.pages.merchant.subscriptions')->with([
            'paystack_response' => $response->json(),
            'success'           => 'Subscription successfully enabled!',
        ]);
    }

    public function loginpage()
    {
        if (Session::get('merchantId')) {
            return redirect('/merchant/dashboard');
        }
        $globNotice = Notice::where('published', 1)->first();
        return view('frontEnd.layouts.pages.login')->with(compact('globNotice'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phoneOremail' => 'required',
            'password'     => 'required',
        ]);

        $merchantChedk = Merchant::orWhere('emailAddress', $request->phoneOremail)
            ->orWhere('phoneNumber', $request->phoneOremail)
            ->first();

        if ($merchantChedk) {

            if ($merchantChedk->status == 0 || $merchantChedk->verifyToken == 0) {
                Toastr::warning('warning', 'Your account is currently undergoing a review process. During this time, you might not be able to access your account.');

                return redirect()->back();
            } else {

                if (password_verify($request->password, $merchantChedk->password)) {
                    $merchantId = $merchantChedk->id;
                    Session::put('merchantId', $merchantId);
                    Toastr::success('success', 'Thanks , You are login successfully');

                    return redirect('/merchant/dashboard');

                } else {
                    Toastr::error('Opps!', 'Sorry! your password wrong');

                    return redirect()->back();
                }

            }

        } else {
            Toastr::error('Opps!', 'Opps! you have no account');

            return redirect()->back();
        }

    }

    public function phoneVerifyForm()
    {
        $phoneverify = Session::get('phoneverify');

        if ($phoneverify == ! null) {
            return view('frontEnd.layouts.pages.merchant.verify');
        } else {
            Toastr::error('!Opps', 'Your process is invalid');

            return redirect('/');
        }

    }

    public function phoneresendcode(Request $request)
    {
        $merchantInfo              = Merchant::where('phoneNumber', Session::get('phoneverify'))->first();
        $verifyToken               = rand(1111, 9999);
        $merchantInfo->verifyToken = $verifyToken;
        $merchantInfo->save();
        $url  = "http://premium.mdlsms.com/smsapi";
        $data = [
            "api_key"  => "C20005455f867568bd8c02.20968541",
            "type"     => "Text",
            "contacts" => '0' . $merchantInfo->phoneNumber,
            "senderid" => "8809612440738",
            "msg"      => "Your verify Token is $verifyToken ,Thanks for using our services",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        Toastr::success('!Done', 'We send a OTP in your phone');

        return redirect('merchant/phone-verify');

    }

    public function phoneVerify(Request $request)
    {
        $this->validate($request, [
            'verifyToken' => 'required',
        ]);
        $verified = Merchant::where('phoneNumber', Session::get('phoneverify'))->first();
        // dd($verified);
        $verifydbtoken   = $verified->verifyToken;
        $verifyformtoken = $request->verifyToken;

        if ($verifydbtoken == $verifyformtoken) {
            $verified->verifyToken = 1;
            $verified->status      = 1;
            $verified->save();
            Session::put('merchantId', $verified->id);
            Session::forget('phoneverify');
            Toastr::success('Your account is verified', 'success!');

            return redirect('merchant/dashboard');
        } else {
            Toastr::error('sorry your verify token wrong', 'Opps!');

            return redirect()->back();
        }

    }

    // Merchant Login Function End

    public function dashboard()
    {
        $merchantID = Session::get('merchantId');
        $data       = [];
        //this month
        $data['m_pending']         = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 1)->count();
        $data['m_pick']            = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 2)->count();
        $data['m_await']           = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 3)->count();
        $data['m_deliver']         = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now()->month)->where('status', 4)->count();
        $data['m_partial_deliver'] = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 6)->count();
        $data['m_return']          = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 8)->count();
        $data['m_da']              = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 10)->count();
        $data['m_paid']            = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 11)->count();
        $data['m_hold']            = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 5)->count();
        $data['m_wallet']          = RemainTopup::where('merchant_id', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->sum('amount');
        $data['m_returntohub']     = Parcel::where('merchantId', $merchantID)->whereYear('updated_at', now())->whereMonth('updated_at', now())->where('status', 7)->count();

        //total
        $data['t_pending']         = Parcel::where('merchantId', $merchantID)->where('status', 1)->count();
        $data['t_pick']            = Parcel::where('merchantId', $merchantID)->where('status', 2)->count();
        $data['t_await']           = Parcel::where('merchantId', $merchantID)->where('status', 3)->count();
        $data['t_deliver']         = Parcel::where('merchantId', $merchantID)->where('status', 4)->count();
        $data['t_partial_deliver'] = Parcel::where('merchantId', $merchantID)->where('status', 6)->count();
        $data['t_return']          = Parcel::where('merchantId', $merchantID)->where('status', 8)->count();
        $data['t_da']              = Parcel::where('merchantId', $merchantID)->where('status', 10)->count();
        $data['t_hold']            = Parcel::where('merchantId', $merchantID)->where('status', 5)->count();
        $data['t_paid']            = Parcel::where('merchantId', $merchantID)->where('status', 11)->count();
        $data['t_returntohub']     = Parcel::where('merchantId', $merchantID)->where('status', 7)->count();

        $data['parcels'] = Parcel::where('merchantId', $merchantID)->orderBy('updated_at', 'DESC')->limit(50)->with('merchant', 'parcelnote', 'pickupcity', 'deliverycity', 'pickuptown', 'deliverytown')
            ->get();

        $months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December",
        ];

        // Fetch delivered parcels data with full month and year
        $deliveredParcelsRaw = Parcel::selectRaw("DATE_FORMAT(updated_at, '%M %Y') as month_year, COUNT(*) as count")
            ->where('merchantId', $merchantID)
            ->whereIn('status', [4, 6]) // Adjust the status as per requirement
            ->whereYear('updated_at', date('Y'))
            ->groupBy('month_year')
            ->orderByRaw("MONTH(updated_at)") // Ensure correct order
            ->pluck('count', 'month_year');   // Fetch as key-value pair (month_year => count)

        // Fetch pickup parcels data with full month and year
        $pickupParcelsRaw = Parcel::selectRaw("DATE_FORMAT(created_at, '%M %Y') as month_year, COUNT(*) as count")
            ->where('merchantId', $merchantID)
            ->whereIn('status', [2, 3, 4, 5, 6, 7, 8, 10, 11, 12]) // Explicitly list the statuses
            ->whereYear('created_at', date('Y'))
            ->groupBy('month_year')
            ->orderByRaw("MONTH(created_at)") // Ensure correct order
            ->pluck('count', 'month_year');   // Fetch as key-value pair (month_year => count)

        // Fill missing months with 0
        $deliveredParcels = [];
        $pickupParcels    = [];

        foreach ($months as $month) {
            $fullMonthYear = $month . ' ' . date('Y'); // Append the current year to the month

            $deliveredParcels[] = [
                'month' => $fullMonthYear,
                'count' => $deliveredParcelsRaw[$fullMonthYear] ?? 0, // Use existing count, otherwise 0
            ];

            $pickupParcels[] = [
                'month' => $fullMonthYear,
                'count' => $pickupParcelsRaw[$fullMonthYear] ?? 0,
            ];
        }

        // Pass to view
        $data['deliveredParcels'] = $deliveredParcels;
        $data['pickupParcels']    = $pickupParcels;
        $data['notice']           = Disclamer::find(1);

        $merchantdata     = Merchant::where('id', $merchantID)->with('parcels')->first();
        $data['merchant'] = $merchantdata;

        // return merchant
        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();
        $merchant   = Merchant::select(['id', 'companyName', 'paymentMethod'])
            ->where('id', $merchantID)
            ->with(['parcels' => function ($query) use ($parceltype) {
                return $query->where('status', '=', $parceltype->id)
                    ->where('deliveryCharge', '>', 0)
                    ->where('pay_return', 0);
            }])->first();
        $retmercharge = 0;

        if ($merchant->parcels->count() > 0 && $merchant->pay_return == 0) {
            foreach ($merchant->parcels as $p) {
                $retmercharge += $p->deliveryCharge + $p->tax + $p->insurance;
            }

            $data['retmercharge'] = $retmercharge;

        }
        // MErchant Due

        $merchantDueamount = 0;
        foreach ($merchantdata->parcels as $parcel) {
            if ($parcel->status == 4 || $parcel->status == 6) {
                // $due = $due + ($parcel->codCharge + $parcel->deliveryCharge - $parcel->cod);
                $merchantDueamount = $merchantDueamount + $parcel->merchantDue;
            }
        }
        $data['merchantDueamount'] = $merchantDueamount;
        // Merchant Paid
        // Total sum of 'merchantPaid' for the specific merchant
        $merchantspaid         = Parcel::where('merchantId', $merchantID)->sum('merchantPaid');
        $data['merchantspaid'] = $merchantspaid;


        return view('frontEnd.layouts.pages.merchant.dashboard', $data);
    }

    // Merchant Dashboard
    public function profile()
    {
        $profileinfos = Merchant::all();

        return view('frontEnd.layouts.pages.merchant.profile', compact('profileinfos',));

    }

    public function profileEdit()
    {
        
        $merchantInfo = Merchant::find(Session::get('merchantId'));
        $nearestzones = Nearestzone::where('status', 1)->get();
        $results = DB::table('paymentapis')->where('id', 1)->first();
        $merchantSubsPlan = MerchantSubscriptions::where('merchant_id', $merchantInfo->id)->where('is_active', 1)->first();
        // dd($merchantSubsPlan);
                
        $SubsHistos = MerchantSubscriptions::with('plan')
        ->where('merchant_id', Session::get('merchantId'))
        ->orderByDesc('created_at')
        ->limit(20)
        ->get()
        ->map(function ($item) {
            $item->formatted_date = \Carbon\Carbon::parse($item->assign_time)->format('d/m/Y');
            $item->formatted_time = \Carbon\Carbon::parse($item->assign_time)->format('j M Y') . ' To ' . \Carbon\Carbon::parse($item->expired_time)->format('j M Y');
            return $item;
        });       

        return view('frontEnd.layouts.pages.merchant.profileedit', compact('nearestzones', 'merchantInfo', 'results', 'merchantSubsPlan', 'SubsHistos'));

    }
    // public function subscription_history()
    // {
    //     $merchantInfo = Merchant::find(Session::get('merchantId'));
    //     $merchantSubsPlan = MerchantSubscriptions::where('merchant_id', $merchantInfo->id)->get();       
    //     return view('frontEnd.layouts.pages.merchant.profileedit', compact('nearestzones', 'merchantInfo', 'results', 'merchantSubsPlan'));

    // }

    public function support()
    {
        return view('frontEnd.layouts.pages.merchant.support');
    }

    // Merchant Profile Edit
    public function profileUpdate(Request $request)
    {
        $update_merchant = Merchant::find(Session::get('merchantId'));

        $update_image = $request->file('logo');

        if ($update_image) {
            $file       = $request->file('logo');
            $name       = $file->getClientOriginalName();
            $uploadPath = 'uploads/merchant/';
            File::delete(public_path() . 'uploads/merchant', $update_merchant->logo);
            $file->move($uploadPath, $name);
            $fileUrl = $uploadPath . $name;
        } else {
            $fileUrl = $update_merchant->logo;
        }

        $update_merchant->logo                  = $fileUrl;
        $update_merchant->phoneNumber           = $request->phoneNumber;
        $update_merchant->pickLocation          = $request->pickLocation;
        $update_merchant->nearestZone           = $request->nearestZone;
        $update_merchant->pickupPreference      = $request->pickupPreference;
        $update_merchant->paymentMethod         = $request->paymentMethod;
        $update_merchant->withdrawal            = $request->withdrawal;
        $update_merchant->nameOfBank            = $request->nameOfBank;
        $update_merchant->beneficiary_bank_code = $request->beneficiary_bank_code;
        $update_merchant->bankBranch            = $request->bankBranch;
        $update_merchant->bankAcHolder          = $request->bankAcHolder;
        $update_merchant->bankAcNo              = $request->bankAcNo;
        $update_merchant->bkashNumber           = $request->bkashNumber;
        $update_merchant->roketNumber           = $request->roketNumber;
        $update_merchant->nogodNumber           = $request->nogodNumber;
        $update_merchant->save();

        return redirect()->back()->with('success', 'Your account update successfully');
    }

    // Merchant Profile Update
    public function logout()
    {
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');

        return redirect('/merchant/login');
    }

// Merchant Logout

    //Parcel Oparation
    public function parcelcreate()
    {

        
        $delivery        = Deliverycharge::where('status', 1)->get();
        $merchantDetails = Merchant::find(Session::get('merchantId'));
        $merchantSubsPlan = MerchantSubscriptions::with('plan')->where('merchant_id', Session::get('merchantId'))->where('is_active', 1)->first();

        Session::forget('codpay');
        Session::forget('pdeliverycharge');
        Session::forget('pcodecharge');
        Session::forget('mtax');
        Session::forget('minsurance');

        Session::forget('deliverycharge');
        Session::forget('exdeliverycharge');
        Session::forget('codcharge');
        Session::forget('codpay');
        Session::forget('pdeliverycharge');
        Session::forget('pcodecharge');
        Session::forget('mtax');
        Session::forget('minsurance');

        return view('frontEnd.layouts.pages.merchant.parcelcreate', compact('delivery', 'merchantDetails', 'merchantSubsPlan'));
    }

     public function parcelbulkupload()
    {

        return view('frontEnd.layouts.pages.merchant.parcelbulkupload');
    }
     public function bulkimporttemplate(): StreamedResponse
    {
        // Option A: serve a static file placed in storage/app/templates/parcel_template.csv
        // return Storage::download('templates/parcel_template.csv');

        // Option B: stream on the fly so you never keep a copy on disk
        $headings = [
            'CustomerName(*)', 'ProductName(*)',
            'PickupCity', 'PickupTown',
            'DeliveryCity', 'DeliveryTown',
            'ParcelType', 'PhoneNumber(*)',
            'PaymentType', 'CashCollectionAmount(*)',
            'PackageValue(*)', 'OrderNumber(*)',
            'ProductColor(*)', 'Weight(*)', 'ProductQty(*)',
            'DeliveryAddress(*)', 'Note(*)'
        ];

        $callback = fn() => fopen('php://output', 'w') && fputcsv($stdout = fopen('php://output', 'w'), $headings);
        return response()->streamDownload($callback,'parcel_template.csv',['Content-Type' => 'text/csv']);
    }
    public function postbulkimport(Request $request)
    {
        /* -----------------------------------------------------------------
           1.  Decode & filter blank rows (extra safety)
        ----------------------------------------------------------------- */
        $rows = collect(json_decode($request->input('payload', '[]'), true))
                ->filter(fn ($r) => collect($r)->except('rownum')
                         ->filter()->isNotEmpty())
                ->values();

        if ($rows->isEmpty()) {
            return back()->withErrors('Nothing to import — sheet was blank.')
                         ->withInput();
        }

        /* -----------------------------------------------------------------
           2.  Fetch merchant & (optionally) subscription once
        ----------------------------------------------------------------- */
        $merchant = Merchant::findOrFail(Session::get('merchantId'));
        $activeSubPlan = MerchantSubscriptions::with('plan')
                            ->where('merchant_id', $merchant->id)
                            ->where('is_active', 1)
                            ->first();

        /* -----------------------------------------------------------------
           3.  Per‑row validation rules    (same fields as single form)
        ----------------------------------------------------------------- */
        $rules = [
            'percelType'     => 'required|in:1,2,3',
            'name'           => 'required|string|max:100',
            'order_number'   => 'required|string|max:100',
            'address'        => 'required|string',
            'phonenumber'    => 'required|string|max:20',
            'productName'    => 'required|string|max:255',
            'productQty'     => 'required|integer|min:1',
            'cod'            => 'required|numeric|min:0',
            'payment_option' => 'required|in:1,2',
            'weight'         => 'required|numeric|min:1',
            'note'           => 'nullable|string',
            'pickuptown'     => 'required|integer|exists:towns,id',
            'pickupcity'     => 'required|integer|exists:cities,id',
            'deliverycity'   => 'required|integer|exists:cities,id',
            'deliverytown'   => 'required|integer|exists:towns,id',
        ];

        /* collect row‑level errors so we can report them all at once */
        $rowErrors = [];

        /* -----------------------------------------------------------------
           4.  Main DB transaction  (everything rolls back if ANY row fails)
        ----------------------------------------------------------------- */
        DB::beginTransaction();
        // try {

            foreach ($rows as $index => $row) {

                /* ---- validate this row ---- */
                $v = Validator::make($row, $rules);
                if ($v->fails()) {
                    $rowErrors[$index + 1] = $v->errors()->all(); // +1 for human row #
                    continue;            // skip processing but keep validating others
                }

                /* ---- all helpers your single function uses ---- */
                $charge = \App\ChargeTarif::where('pickup_cities_id', $row['pickupcity'])
                           ->where('delivery_cities_id', $row['deliverycity'])
                           ->firstOrFail();

                $town   = \App\Town::where('id', $row['deliverytown'])
                           ->where('cities_id', $row['deliverycity'])
                           ->firstOrFail();

                $codAmt     = $row['cod']            ? remove_commas($row['cod'])            : 0;
                $packageAmt = $row['package_value']  ?? 0;
                $weight     = $row['weight'] >= 1    ? $row['weight']                       : 1;

                /* delivery charge */
                $extraWeight     = $weight - 1;
                $deliverycharge  = ($charge->deliverycharge + $town->towncharge)
                                 + ($extraWeight > 0 ? $extraWeight * $charge->extradeliverycharge : 0);

                /* subscription discount */
                if ($activeSubPlan && $activeSubPlan->plan->del_crg_discount_percentage) {
                    $pct = $activeSubPlan->plan->del_crg_discount_percentage;
                    $pct = $pct > 1 ? $pct / 100 : $pct;
                    $deliverycharge -= round($deliverycharge * $pct, 2);
                }

                /* tax */
                $tax = round($deliverycharge * $charge->tax / 100, 2);
                // dd($deliverycharge,$tax);

                /* COD / insurance / merchant amounts */
                if ($row['payment_option'] == 2) {             // pay on delivery (COD)
                    $insurance = round($codAmt * $charge->insurance / 100, 2);
                    if ($merchant->ins_cal_permission == 0 ||
                       ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                        $insurance = 0;
                    }

                    $codcharge = round($codAmt * $charge->codcharge / 100, 2);
                    if ($merchant->cod_cal_permission == 0 ||
                       ($activeSubPlan && $activeSubPlan->plan->cod_permission == 0)) {
                        $codcharge = 0;
                    }

                    $merchantAmount = $codAmt - ($deliverycharge + $codcharge + $tax + $insurance);
                    $merchantDue    = $merchantAmount;

                } else {                                  // prepaid
                    $insurance = round($packageAmt * $charge->insurance / 100, 2);
                    if ($merchant->ins_cal_permission == 0 ||
                       ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                        $insurance = 0;
                    }

                    $totalDelCharge = $deliverycharge + $tax + $insurance;
                    if ($merchant->balance < $totalDelCharge) {
                        $rowErrors[$index + 1][] = 'Wallet balance too low.';
                        continue;
                    }

                    $merchant->decrement('balance', $totalDelCharge);

                    $codcharge      = 0;
                    $merchantAmount = 0;
                    $merchantDue    = 0;
                }

                /* ---- create Parcel & related rows ---- */
                $parcel = Parcel::create([
                    'invoiceNo'          => $row['invoiceno'] ?? null,
                    'merchantId'         => $merchant->id,
                    'cod'                => $codAmt,
                    'package_value'      => $packageAmt,
                    'tax'                => $tax,
                    'insurance'          => $merchant->ins_cal_permission ? $insurance : 0,
                    'percelType'         => $row['percelType'],
                    'order_number'       => $row['order_number'],
                    'payment_option'     => $row['payment_option'],
                    'recipientName'      => $row['name'],
                    'recipientAddress'   => $row['address'],
                    'recipientPhone'     => $row['phonenumber'],
                    'productWeight'      => $weight,
                    'trackingCode'       => 'ZD' . mt_rand(1111111111, 9999999999),
                    'note'               => $row['note'] ?? 'Pending Pickup',
                    'deliveryCharge'     => $deliverycharge,
                    'codCharge'          => $merchant->cod_cal_permission ? $codcharge : 0,
                    'productPrice'       => $row['productPrice'] ?? null,
                    'productName'        => $row['productName'],
                    'productQty'         => $row['productQty'],
                    'productColor'       => $row['productColor'] ?? null,
                    'merchantAmount'     => $merchantAmount,
                    'merchantDue'        => $merchantDue,
                    'pickup_cities_id'   => $row['pickupcity'],
                    'delivery_cities_id' => $row['deliverycity'],
                    'pickup_town_id'     => $row['pickuptown'],
                    'delivery_town_id'   => $row['deliverytown'],
                    'codType'            => 1,
                    'status'             => 1,
                ]);

                if ($row['payment_option'] == 1) {
                    RemainTopup::create([
                        'parcel_id'     => $parcel->id,
                        'parcel_status' => 1,
                        'merchant_id'   => $merchant->id,
                        'amount'        => $deliverycharge + $tax + $insurance,
                    ]);
                }

                History::create([
                    'name'      => "Customer: {$parcel->recipientName}<br><b>(Created By:)</b>{$merchant->companyName}",
                    'parcel_id' => $parcel->id,
                    'done_by'   => $merchant->companyName,
                    'status'    => 'Parcel Created By ' . $merchant->companyName,
                    'note'      => $parcel->note,
                    'date'      => $parcel->updated_at,
                ]);

                Parcelnote::create([
                    'parcelId' => $parcel->id,
                    'note'     => 'Pending Pickup',
                ]);
            }

            /* if any row collected errors, roll back */
            if (!empty($rowErrors)) {
                DB::rollBack();
                return back()
                       ->withErrors(['import' => $rowErrors])
                       ->withInput();
            }

            DB::commit();

        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     return back()->withErrors($e->getMessage())->withInput();
        // }

        Toastr::success('Success!', "{$rows->count()} parcels imported.");
        return back();
    }

    public function parcelstore(Request $request)
    {
        $this->validate($request, [
            'percelType'     => 'required',
            'name'           => 'required',
            'order_number'   => 'required',
            'address'        => 'required',
            'phonenumber'    => 'required',
            'productName'    => 'required',
            'productQty'     => 'required',
            'cod'            => 'required',
            'payment_option' => 'required',
            'weight'         => 'required',
            'note'           => 'required',
            'pickuptown'     => 'required',
            'pickupcity'     => 'required',
            'deliverycity'   => 'required',
            'deliverytown'   => 'required',
        ]);

        // dd($request->all());
        $merchant = Merchant::find(Session::get('merchantId'));

        $charge     = \App\ChargeTarif::where('pickup_cities_id', $request->pickupcity)->where('delivery_cities_id', $request->deliverycity)->first();
        $town       = \App\Town::where('id', $request->deliverytown)->where('cities_id', $request->deliverycity)->first();
        $codAmt     = $request->cod ? remove_commas($request->cod) : 0;
        $packageAmt = $request->package_value ? remove_commas($request->package_value) : 0;
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $merchant->id)->where('is_active', 1)->first();


        if ($request->weight > 1 || $request->weight != null) {
            $extraweight    = $request->weight - 1;
            $deliverycharge = ($charge->deliverycharge + $town->towncharge) + ($extraweight * $charge->extradeliverycharge);
            $weight         = $request->weight;
        } else {
            $deliverycharge = $charge->deliverycharge + $town->towncharge;
            $weight         = 1;
        }

        // Discout delivery charge base on subscribtions plan
        if ($activeSubPlan && isset($activeSubPlan->plan->del_crg_discount_percentage)) {
            $percentage = $activeSubPlan->plan->del_crg_discount_percentage;
        
            // If percentage is stored as whole number (e.g., 10 for 10%)
            if ($percentage > 1) {
                $percentage = $percentage / 100;
            }
        
            $discountCrg = $deliverycharge * $percentage;
            $deliverycharge -= round($discountCrg, 2); // Round to 2 decimal places if needed
        }

        // Tax Calculation And Insurance calculation
        $tax = $deliverycharge * $charge->tax / 100;
        $tax = round($tax, 2);

        if ($request->payment_option == 2) {
            // 2 for cash on delivery
            // $state = Deliverycharge::find($request->package);

            $insurance = $codAmt * $charge->insurance / 100;
            if ($merchant->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                $insurance = 0; // Override if permission is disabled
            }
            $insurance = round($insurance, 2);

            if ($charge) {
                $codcharge = ($codAmt * $charge->codcharge) / 100;
                if ($merchant->cod_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->cod_permission == 0)) {
                    $codcharge = 0; // Override if permission is disabled
                }
            } else {
                $codcharge = 0;
            }

            $merchantAmount = ($codAmt) - ($deliverycharge + $codcharge + $tax + $insurance);
            $merchantDue    = ($codAmt) - ($deliverycharge + $codcharge + $tax + $insurance);

        } else {
            $insurance = $packageAmt * $charge->insurance / 100;
            if ($merchant->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                $insurance = 0; // Override if permission is disabled
            }
            $insurance = round($insurance, 2);
            $totalDelCharge = $deliverycharge + $tax + $insurance;

            if ($merchant->balance < $totalDelCharge) {
                session()->flash('message', 'Wallet Balance is low. Please
                top up.');

                return redirect()->back();
            }

            $merchant->balance = $merchant->balance - $totalDelCharge;
            $merchant->save();
            $codcharge      = 0;
            $merchantAmount = 0;
            $merchantDue    = 0;
        }
        $store_parcel                     = new Parcel();
        $store_parcel->invoiceNo          = $request->invoiceno;
        $store_parcel->merchantId         = $merchant->id;
        $store_parcel->cod                = $codAmt;
        $store_parcel->package_value      = $packageAmt ? $packageAmt : 0;
        $store_parcel->tax                = $tax;
        $store_parcel->insurance          = $merchant->ins_cal_permission == 0 ? 0 : $insurance;
        $store_parcel->percelType         = $request->percelType;
        $store_parcel->order_number       = $request->order_number;
        $store_parcel->payment_option     = $request->payment_option;
        $store_parcel->recipientName      = $request->name;
        $store_parcel->recipientAddress   = $request->address;
        $store_parcel->recipientPhone     = $request->phonenumber;
        $store_parcel->productWeight      = $weight;
        $store_parcel->trackingCode       = 'ZD' . mt_rand(1111111111, 9999999999);
        $store_parcel->note               = $request->note;
        $store_parcel->deliveryCharge     = $deliverycharge;
        $store_parcel->codCharge          = $merchant->cod_cal_permission == 0 ? 0 : $codcharge;
        $store_parcel->reciveZone         = $request->reciveZone;
        $store_parcel->productPrice       = $request->productPrice;
        $store_parcel->productName        = $request->productName;
        $store_parcel->productQty         = $request->productQty;
        $store_parcel->productColor       = $request->productColor;
        $store_parcel->merchantAmount     = $merchantAmount;
        $store_parcel->merchantDue        = $merchantDue;
        $store_parcel->orderType          = $request->package ?? 0;
        $store_parcel->pickup_cities_id   = $request->pickupcity;
        $store_parcel->delivery_cities_id = $request->deliverycity;
        $store_parcel->pickup_town_id     = $request->pickuptown;
        $store_parcel->delivery_town_id   = $request->deliverytown;
        $store_parcel->codType            = 1;
        $store_parcel->status             = 1;
        $store_parcel->save();

        if ($request->payment_option == 1) {
            RemainTopup::create([
                'parcel_id'     => $store_parcel->id,
                'parcel_status' => 1,
                'merchant_id'   => $merchant->id,
                'amount'        => $deliverycharge + $tax + $insurance,
            ]);
        }
        $history            = new History();
        $history->name      = "Customer: " . $store_parcel->recipientName . "<br><b>(Created By:  )</b>" . $merchant->companyName;
        $history->parcel_id = $store_parcel->id;
        $history->done_by   = $merchant->companyName;
        $history->status    = 'Parcel Created By ' . $merchant->companyName;
        $history->note      = $request->note ? $request->note : 'Pending Pickup';
        $history->date      = $store_parcel->updated_at;
        $history->save();

        $note           = new Parcelnote();
        $note->parcelId = $store_parcel->id;
        $note->note     = 'Pending Pickup';
        $note->save();

        Toastr::success('Success!', 'Thanks! your parcel add successfully');

        session()->flash('open_url', url('/merchant/parcel/invoice/' . $store_parcel->id));
        return redirect()->back();
    }

    public function parcelupdate(Request $request)
    {
        $this->validate($request, [
            'percelType'     => 'required',
            'name'           => 'required',
            'order_number'   => 'required',
            'address'        => 'required',
            'phonenumber'    => 'required',
            'productName'    => 'required',
            'productQty'     => 'required',
            'cod'            => 'required',
            'payment_option' => 'required',
            'weight'         => 'required',
            'note'           => 'required',
            'pickuptown'     => 'required',
            'pickupcity'     => 'required',
            'deliverycity'   => 'required',
            'deliverytown'   => 'required',
        ]);
        $merchant   = Merchant::find(Session::get('merchantId'));
        $charge     = \App\ChargeTarif::where('pickup_cities_id', $request->pickupcity)->where('delivery_cities_id', $request->deliverycity)->first();
        $town       = \App\Town::where('id', $request->deliverytown)->where('cities_id', $request->deliverycity)->first();
        $codAmt     = $request->cod ? remove_commas($request->cod) : 0;
        $packageAmt = $request->package_value ? remove_commas($request->package_value) : 0;
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $merchant->id)->where('is_active', 1)->first();


        if ($request->weight > 1 || $request->weight != null) {
            $extraweight    = $request->weight - 1;
            $deliverycharge = ($charge->deliverycharge + $town->towncharge) + ($extraweight * $charge->extradeliverycharge);
            $weight         = $request->weight;
        } else {
            $deliverycharge = $charge->deliverycharge + $town->towncharge;
            $weight         = 1;
        }

        // Discout delivery charge base on subscribtions plan
        if ($activeSubPlan && isset($activeSubPlan->plan->del_crg_discount_percentage)) {
            $percentage = $activeSubPlan->plan->del_crg_discount_percentage;
        
            // If percentage is stored as whole number (e.g., 10 for 10%)
            if ($percentage > 1) {
                $percentage = $percentage / 100;
            }
        
            $discountCrg = $deliverycharge * $percentage;
            $deliverycharge -= round($discountCrg, 2); // Round to 2 decimal places if needed
        }
        // Tax Calculation And Insurance calculation
        $tax = $deliverycharge * $charge->tax / 100;
        $tax = round($tax, 2);

        if ($request->payment_option == 2) {
            // 2 for cash on delivery
            $insurance = $codAmt * $charge->insurance / 100;
            if ($merchant->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                $insurance = 0; // Override if permission is disabled
            }
            $insurance = round($insurance, 2);
            // dd($insurance);
            // $state = Deliverycharge::find($request->package);

            if ($charge) {
                $codcharge = ($codAmt * $charge->codcharge) / 100;
                if ($merchant->cod_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->cod_permission == 0)) {
                    $codcharge = 0; // Override if permission is disabled
                }
            } else {
                $codcharge = 0;
            }

            $merchantAmount = ($codAmt) - ($deliverycharge + $codcharge + $tax + $insurance);
            $merchantDue    = ($codAmt) - ($deliverycharge + $codcharge + $tax + $insurance);

        } else {

            $insurance = $packageAmt * $charge->insurance / 100;
            if ($merchant->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                $insurance = 0; // Override if permission is disabled
            }
            $insurance = round($insurance, 2);

            $parcelData = Parcel::find($request->hidden_id);
            if ($parcelData->payment_option == 1) {
                $RemainTopup       = RemainTopup::where('parcel_id', $request->hidden_id)->first();
                $RemainTopupamount = $RemainTopup->amount;
                $merchant->balance = $merchant->balance + $RemainTopupamount;
                $merchant->save();
            }

            $totalDelCharge = $deliverycharge + $tax + $insurance;

            if ($merchant->balance < $totalDelCharge) {
                session()->flash('message', 'Wallet Balance is low. Please
                top up.');

                return redirect()->back();
            }

            $merchant->balance = $merchant->balance - $totalDelCharge;
            $merchant->save();
            $codcharge      = 0;
            $merchantAmount = 0;
            $merchantDue    = 0;
        }

        $store_parcel                     = Parcel::find($request->hidden_id);
        $store_parcel->cod                = $codAmt;
        $store_parcel->package_value      = $packageAmt;
        $store_parcel->tax                = $tax;
        $store_parcel->insurance          = $merchant->ins_cal_permission == 0 ? 0 : $insurance;
        $store_parcel->percelType         = $request->percelType;
        $store_parcel->order_number       = $request->order_number;
        $store_parcel->payment_option     = $request->payment_option;
        $store_parcel->recipientName      = $request->name;
        $store_parcel->recipientAddress   = $request->address;
        $store_parcel->recipientPhone     = $request->phonenumber;
        $store_parcel->productWeight      = $weight;
        $store_parcel->note               = $request->note ?? '';
        $store_parcel->deliveryCharge     = $deliverycharge;
        $store_parcel->codCharge          = $merchant->cod_cal_permission == 0 ? 0 : $codcharge;
        $store_parcel->reciveZone         = $request->reciveZone;
        $store_parcel->productPrice       = $request->productPrice;
        $store_parcel->productName        = $request->productName;
        $store_parcel->productQty         = $request->productQty;
        $store_parcel->productColor       = $request->productColor;
        $store_parcel->merchantAmount     = $merchantAmount;
        $store_parcel->merchantDue        = $merchantDue;
        $store_parcel->orderType          = $request->package ?? 0;
        $store_parcel->pickup_cities_id   = $request->pickupcity;
        $store_parcel->delivery_cities_id = $request->deliverycity;
        $store_parcel->pickup_town_id     = $request->pickuptown;
        $store_parcel->delivery_town_id   = $request->deliverytown;
        $store_parcel->codType            = 1;
        $store_parcel->status             = 1;
        if ($store_parcel->agentId) {
            $store_parcel->agentAmount = $codAmt;
        }
        $store_parcel->save();
        if ($request->payment_option == 1) {

            $RemainTopup = RemainTopup::where('parcel_id', $request->hidden_id)->first();
            if ($RemainTopup) {
                $RemainTopup->amount = $deliverycharge + $tax + $insurance;
                $RemainTopup->save();
            } else {
                $RemainTopup                = new RemainTopup();
                $RemainTopup->parcel_id     = $request->hidden_id;
                $RemainTopup->parcel_status = 1;
                $RemainTopup->merchant_id   = $merchant->id;
                $RemainTopup->amount        = $deliverycharge + $tax + $insurance;
                $RemainTopup->save();
            }

        }
        // delete parcel note
        $Parcelnote = Parcelnote::where('parcelId', $request->hidden_id)->first();
        if ($Parcelnote) {
            $Parcelnote->note = 'Pending Pickup';
            $Parcelnote->save();
        } else {
            $Parcelnote           = new Parcelnote();
            $Parcelnote->parcelId = $request->hidden_id;
            $Parcelnote->note     = 'Pending Pickup';
            $Parcelnote->save();
        }

        Toastr::success('Success!', 'Thanks! your parcel update successfully');

        return redirect()->back();
    }

    public function pickuprequest(Request $request)
    {
        $this->validate($request, [
            'pickupAddress' => 'required',
        ]);

        $date       = date('Y-m-d');
        $findpickup = Pickup::where('date', $date)->Where('merchantId', Session::get('merchantId'))->count();

        if ($findpickup) {
            Toastr::error('Opps!', 'Sorry! your pickup request already pending');

            return redirect()->back();
        } else {
            $store_pickup                = new Pickup();
            $store_pickup->merchantId    = Session::get('merchantId');
            $store_pickup->pickuptype    = $request->pickuptype;
            $store_pickup->area          = $request->area ?? 1;
            $store_pickup->pickupAddress = $request->pickupAddress;
            $store_pickup->note          = $request->note;
            $store_pickup->date          = $date;
            $store_pickup->estimedparcel = $request->estimedparcel;
            $store_pickup->save();
            Toastr::success('Success!', 'Thanks! your pickup request send  successfully');

            try {
                $merchant = Merchant::find(Session::get('merchantId'));
                Mail::to([
                    'e-tailing@zidrop.com',
                ])->send(new NewPickupRequestEmail($merchant, $store_pickup));

            } catch (\Exception $exception) {
                Log::info('New Pickup Request Mail Error: ' . $exception->getMessage());
            }

            return redirect()->back();
        }

    }

    public function pickup()
    {
        $show_data = DB::table('pickups')
            ->where('pickups.merchantId', Session::get('merchantId'))
            ->orderBy('pickups.id', 'DESC')
            ->select('pickups.*')
            ->get();
        $deliverymen = Deliveryman::where('status', 1)->get();

        return view('frontEnd.layouts.pages.merchant.pickup', compact('show_data', 'deliverymen'));
    }

    public function parcels(Request $request)
    {
        $filter = $request->filter_id;

        if ($request->trackId != null) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.trackingCode', $request->trackId)
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } elseif ($request->phoneNumber != null) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.recipientPhone', $request->phoneNumber)
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } elseif ($request->startDate != null && $request->endDate != null) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } elseif ($request->phoneNumber != null || $request->phoneNumber != null && $request->startDate != null && $request->endDate != null) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.recipientPhone', $request->phoneNumber)
                ->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } else {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        }
        $slug       = 'all';
        $parceltype = [];
        return view('frontEnd.layouts.pages.merchant.parcels', compact('allparcel', 'slug', 'parceltype'));
    }
    // public function get_parcel_dataOld(Request $request, $slug)
    // {
       
    //     $parceltype = Parceltype::where('slug', $slug)->first();
    //     // Datatable
    //     $start = 0;
    //     if (isset($request->start)) {
    //         $start = $request->start;
    //     }
    //     $draw = 0;
    //     if (isset($request->draw)) {
    //         $draw = $request->draw;
    //     }
    //     $length = 10;
    //     if (isset($request->length)) {
    //         $length = $request->length;
    //     }

    //     $filter = $request->filter_id;

    //     if ($slug == 'all') {
    //         $query = \App\Parcel::select('*')
    //             ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
    //             ->where('merchantId', Session::get('merchantId'))
    //             ->orderBy('updated_at', 'DESC');
    //         if ($request->trackId) {
    //             $query->where('trackingCode', $request->trackId);
    //         } 
    //         if ($request->phoneNumber) {
    //             $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
    //             $query->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
    //         }
    //         if ($request->cname) {
    //             $query->where('recipientName', 'like', '%' . $request->cname . '%');
    //         } 
    //         if ($request->address) {
    //             $query->where('recipientAddress', $request->address);
    //         } 
    //         if ($request->startDate && $request->endDate) {
    //             $startDate = Carbon::parse($request->startDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->endDate)->endOfDay();
    //             $query->whereBetween('created_at', [$startDate, $endDate]);
    //         }
    //         if ($request->upstartDate && $request->upendDate) {
    //             $startDate = Carbon::parse($request->upstartDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->upendDate)->endOfDay();
    //             $query->whereBetween('updated_at', [$startDate, $endDate]);
    //         }
    //         if ($request->UpStatusArray != null) {
    //             $query->whereIn('status', $request->UpStatusArray);
    //         }

    //     } else {

    //         $slug       = $slug;
    //         $parceltype = Parceltype::where('slug', $slug)->first();
    //         $query      = \App\Parcel::select('*')
    //             ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
    //             ->where('merchantId', Session::get('merchantId'))
    //             ->where('status', $parceltype->id)
    //             ->orderBy('updated_at', 'DESC');
            
    //         if ($request->trackId) {
    //             $query->where('trackingCode', $request->trackId);
    //         }

    //         if ($request->phoneNumber) {
    //             $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber);
    //             $query->whereRaw(
    //                 "REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?",
    //                 ["%{$phoneNumber}%"]
    //             );
    //         }

    //         if ($request->cname) {
    //             $query->where('recipientName', 'like', '%' . $request->cname . '%');
    //         }

    //         if ($request->address) {
    //             $query->where('recipientAddress', 'like', '%' . $request->address . '%');
    //         }

    //         if ($request->startDate && $request->endDate) {
    //             $startDate = Carbon::parse($request->startDate)->startOfDay();
    //             $endDate = Carbon::parse($request->endDate)->endOfDay();
    //             $query->whereBetween('created_at', [$startDate, $endDate]);
    //         }

    //         if ($request->upstartDate && $request->upendDate) {
    //             $startDate = Carbon::parse($request->upstartDate)->startOfDay();
    //             $endDate = Carbon::parse($request->upendDate)->endOfDay();
    //             $query->whereBetween('updated_at', [$startDate, $endDate]);
    //         }

    //         if (!empty($request->UpStatusArray)) {
    //             $query->whereIn('status', $request->UpStatusArray);
    //         }

    //     }
    //     $count     = $query->count();
    //     $allparcel = $query->offset($start)->limit($length)->get();

    //     $aparceltypes = Parceltype::limit(3)->get();
    //     // Data table
    //     $data = [
    //         'draw'            => $draw,
    //         'recordsTotal'    => $count,
    //         'recordsFiltered' => $count,
    //         'data'            => [],
    //     ];

    //     foreach ($allparcel as $key => $value) {
    //         $deliverymanInfo = Deliveryman::find($value->deliverymanId);
    //         $merchantInfo    = Merchant::find($value->merchantId);
    //         $parcelstatus    = Parceltype::find($value->status);
    //         $merchantDetails = $value->getMerchantOrSenderDetails();

    //         $datavalue[0] = $value->trackingCode;
    //         $datavalue[1] = '<ul class="action_buttons cust-action-btn"><li class="m-1"><button class="btn btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li><li class="m-1"><a href="' . url('merchant/parcel/in-details/' . $value->id) . '" class="btn btn-secondary px-3" title="Track"><i class="fa fa-info"></i></a></li>';

    //         if ($value->status == 1) {
    //             $datavalue[1] .= '<li class="m-1"><a href="' . url('merchant/parcel/edit/' . $value->id) . '" class="btn btn-danger" title="Edit"><i class="fa fa-edit"></i></a></li>';
    //         }
    //         $datavalue[1] .= '<li class="m-1"><a class="btn btn-primary" href="' . url('merchant/parcel/invoice/' . $value->id) . '" target="_blank"  title="Invoice"><i class="fas fa-list"></i></a></li></ul>';
    //         $datavalue[2] = date('F d, Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
    //         $datavalue[3] = $value->recipientName;
    //         $datavalue[4] = $value->recipientPhone;
    //         $parcelstatus = \App\Parceltype::find($value->status);

    //         if ($parcelstatus != null) {
    //             $datavalue[5] = $parcelstatus->title;
    //         } else {
    //             $datavalue[5] = '';
    //         }
    //         $deliverymanInfo = \App\Deliveryman::find($value->deliverymanId);
    //         if ($value->deliverymanId) {
    //             $datavalue[6] = $deliverymanInfo->name;
    //         } else {
    //             $datavalue[6] = 'Not Asign';
    //         }
    //         $datavalue[7]  = number_format($value->cod, 2);
    //         $datavalue[8]  = number_format($value->package_value, 2);
    //         $datavalue[9]  = number_format($value->deliveryCharge, 2);
    //         $datavalue[10] = number_format($value->codCharge, 2);
    //         $datavalue[11] = number_format($value->tax, 2);
    //         $datavalue[12] = number_format($value->insurance, 2);
    //         $datavalue[13] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);
    //         $datavalue[14] = date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
    //         if ($value->merchantpayStatus == null) {
    //             $datavalue[15] = '<button class="btn " style="background-color: #7C7C7C; color:white"> NULL </button>';
    //         } elseif ($value->merchantpayStatus == 0) {
    //             $datavalue[15] = '<button class="btn " style="background-color: #28A745; color:white"> Processing</button>';
    //         } else {
    //             $datavalue[15] = '<button class="btn " style="background-color: #28A745; color:white"> Paid </button>';
    //         }
    //         $datavalue[16] = $value->id;
    //         $parcelnote    = \App\Parcelnote::where('parcelId', $value->id)->orderBy('id', 'DESC')->first();
    //         if (! empty($parcelnote)) {
    //             $datavalue[17] = $parcelnote->note;
    //         } else {
    //             $datavalue[17] = '';
    //         }

    //         array_push($data['data'], $datavalue);

    //     }

    //     return $data;
    // }
    // ** remove this after developement of way bill
    public function get_parcel_data(Request $request, $slug)
    {
       
        $parceltype = Parceltype::where('slug', $slug)->first();
        // Datatable
        $start = 0;
        if (isset($request->start)) {
            $start = $request->start;
        }
        $draw = 0;
        if (isset($request->draw)) {
            $draw = $request->draw;
        }
        $length = 10;
        if (isset($request->length)) {
            $length = $request->length;
        }

        $filter = $request->filter_id;

        if ($slug == 'all') {
            $query = \App\Parcel::select('*')
                ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
                ->where('merchantId', Session::get('merchantId'))
                ->orderBy('updated_at', 'DESC');
            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            } 
            if ($request->phoneNumber) {
                $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
                $query->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
            }
            if ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            } 
            if ($request->address) {
                $query->where('recipientAddress', $request->address);
            } 
            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::parse($request->startDate)->startOfDay();
                $endDate   = Carbon::parse($request->endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            if ($request->upstartDate && $request->upendDate) {
                $startDate = Carbon::parse($request->upstartDate)->startOfDay();
                $endDate   = Carbon::parse($request->upendDate)->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            }
            if ($request->UpStatusArray != null) {
                $query->whereIn('status', $request->UpStatusArray);
            }

        } else {

            $slug       = $slug;
            $parceltype = Parceltype::where('slug', $slug)->first();
            $query      = \App\Parcel::select('*')
                ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
                ->where('merchantId', Session::get('merchantId'))
                ->where('status', $parceltype->id)
                ->orderBy('updated_at', 'DESC');
            
            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            }

            if ($request->phoneNumber) {
                $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber);
                $query->whereRaw(
                    "REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?",
                    ["%{$phoneNumber}%"]
                );
            }

            if ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            }

            if ($request->address) {
                $query->where('recipientAddress', 'like', '%' . $request->address . '%');
            }

            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::parse($request->startDate)->startOfDay();
                $endDate = Carbon::parse($request->endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($request->upstartDate && $request->upendDate) {
                $startDate = Carbon::parse($request->upstartDate)->startOfDay();
                $endDate = Carbon::parse($request->upendDate)->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            }

            if (!empty($request->UpStatusArray)) {
                $query->whereIn('status', $request->UpStatusArray);
            }

        }
        $count     = $query->count();
        $allparcel = $query->offset($start)->limit($length)->get();

        $aparceltypes = Parceltype::limit(3)->get();
        // Data table
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($allparcel as $key => $value) {
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $merchantInfo    = Merchant::find($value->merchantId);
            $parcelstatus    = Parceltype::find($value->status);
            $merchantDetails = $value->getMerchantOrSenderDetails();



            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" data-parcel_status_update_sl="' . $parcelstatus->sl . '" name="parcel_id[]" form="myform"></form>';
            $datavalue[1] = $value->trackingCode;
            $datavalue[2] = '<ul class="action_buttons cust-action-btn"><li class="m-1"><button class="btn btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li><li class="m-1"><a href="' . url('merchant/parcel/in-details/' . $value->id) . '" class="btn btn-secondary px-3" title="Track"><i class="fa fa-info"></i></a></li>';

            if ($value->status == 1) {
                $datavalue[2] .= '<li class="m-1"><a href="' . url('merchant/parcel/edit/' . $value->id) . '" class="btn btn-danger" title="Edit"><i class="fa fa-edit"></i></a></li>';
            }
            $datavalue[2] .= '<li class="m-1"><a class="btn btn-primary" href="' . url('merchant/parcel/invoice/' . $value->id) . '" target="_blank"  title="Invoice"><i class="fas fa-list"></i></a></li></ul>';
            $datavalue[3] = date('F d, Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            $datavalue[4] = $value->recipientName;
            $datavalue[5] = $value->recipientPhone;
            $parcelstatus = \App\Parceltype::find($value->status);

            if ($parcelstatus != null) {
                $datavalue[6] = $parcelstatus->title;
            } else {
                $datavalue[6] = '';
            }
            $deliverymanInfo = \App\Deliveryman::find($value->deliverymanId);
            if ($value->deliverymanId) {
                $datavalue[7] = $deliverymanInfo->name;
            } else {
                $datavalue[7] = 'Not Asign';
            }
            $datavalue[8]  = number_format($value->cod, 2);
            $datavalue[9]  = number_format($value->package_value, 2);
            $datavalue[10]  = number_format($value->deliveryCharge, 2);
            $datavalue[11] = number_format($value->codCharge, 2);
            $datavalue[12] = number_format($value->tax, 2);
            $datavalue[13] = number_format($value->insurance, 2);
            $datavalue[14] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);
            $datavalue[15] = date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
            if ($value->merchantpayStatus == null) {
                $datavalue[16] = '<button class="btn " style="background-color: #7C7C7C; color:white"> NULL </button>';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[16] = '<button class="btn " style="background-color: #28A745; color:white"> Processing</button>';
            } else {
                $datavalue[16] = '<button class="btn " style="background-color: #28A745; color:white"> Paid </button>';
            }
            $datavalue[17] = $value->id;
            $parcelnote    = \App\Parcelnote::where('parcelId', $value->id)->orderBy('id', 'DESC')->first();
            if (! empty($parcelnote)) {
                $datavalue[18] = $parcelnote->note;
            } else {
                $datavalue[18] = '';
            }

            array_push($data['data'], $datavalue);

        }

        return $data;
    }
    public function parcelstatus($slug)
    {
        $parceltype = Parceltype::where('slug', $slug)->first();
        // var_dump($parceltype->id);
        // exit();
        $slug       = $slug;
        if (request()->month) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now())
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        } else {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        }

        return view('frontEnd.layouts.pages.merchant.parcels', compact('allparcel', 'slug', 'parceltype'));
    }
    public function parcelstatus_month($slug)
    {
        $parceltype = Parceltype::where('slug', $slug)->first();
        $slug       = $slug;
        if (request()->month) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now())
                ->whereYear('parcels.updated_at', now())
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();

        } else {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now())
                ->whereYear('parcels.updated_at', now())
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        }

        // return view('frontEnd.layouts.pages.merchant.month_parcels', compact('allparcel', 'slug', 'parceltype'));
        return view('frontEnd.layouts.pages.merchant.parcels', compact('allparcel', 'slug', 'parceltype'));
    }
    // Remove this after way bill development
    public function parcelstatus_monthTest($slug)
    {
        $parceltype = Parceltype::where('slug', $slug)->first();
        $slug       = $slug;
        if (request()->month) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now())
                ->whereYear('parcels.updated_at', now())
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();

        } else {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.merchantId', Session::get('merchantId'))
                ->where('parcels.status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now())
                ->whereYear('parcels.updated_at', now())
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('updated_at', 'DESC')
                ->get();
        }

        // return view('frontEnd.layouts.pages.merchant.month_parcels', compact('allparcel', 'slug', 'parceltype'));
        return view('frontEnd.layouts.pages.merchant.parcelsTest', compact('allparcel', 'slug', 'parceltype'));
    }

    // public function parcelstatus_month($slug)
    // {
    //     // Fetch the parcel type using the slug
    //     $parceltype = Parceltype::where('slug', $slug)->first();

    //     // Get the current year and current month
    //     $currentYear = now()->year;
    //     $currentMonth = now()->month;

    //     // Check if a specific month is passed via the request (optional)
    //     if (request()->has('month')) {
    //         $month = request()->get('month'); // The month from the request
    //     } else {
    //         $month = $currentMonth; // If no month is specified, use the current month
    //     }

    //     // Query parcels for the current year and current month (or the requested month)
    //     $allparcel = DB::table('parcels')
    //         ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
    //         ->where('parcels.merchantId', Session::get('merchantId'))
    //         ->where('parcels.status', $parceltype->id)
    //         ->whereYear('parcels.updated_at', $currentYear)  // Filter by the current year
    //         ->whereMonth('parcels.updated_at', $month)      // Filter by the requested or current month
    //         ->select('parcels.*',
    //                  'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber',
    //                  'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus',
    //                  'merchants.id as mid')
    //         ->orderBy('parcels.updated_at', 'DESC')  // Order by updated date
    //         ->get();

    //         // echo "<pre>";
    //         // print_r($allparcel);
    //         // echo "</pre>";

    //     // Return the view with the parcels, slug, and parcel type information
    //     return view('frontEnd.layouts.pages.merchant.month_parcels', compact('allparcel', 'slug', 'parceltype'));
    // }

    // public function get_parcel_data_monthOld($slug, Request $request)
    // {
    //     $parceltype = Parceltype::where('slug', $slug)->first();
    //     // Datatable
    //     $start = 0;
    //     if (isset($request->start)) {
    //         $start = $request->start;
    //     }
    //     $draw = 1;
    //     if (isset($request->draw)) {
    //         $draw = $request->draw;
    //     }
    //     $length = 10;
    //     if (isset($request->length)) {
    //         $length = $request->length;
    //     }

    //     $filter = $request->filter_id;

    //     if ($slug == 'all') {
    //         $query = \App\Parcel::select('*')
    //             ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
    //             ->where('merchantId', Session::get('merchantId'))
    //             ->whereMonth('parcels.updated_at', now())
    //             ->orderBy('updated_at', 'DESC');

    //         if ($request->trackId) {
    //             $query->where('trackingCode', $request->trackId);
    //         } elseif ($request->phoneNumber) {
    //             $query->where('recipientPhone', $request->phoneNumber);
    //         } elseif ($request->cname) {
    //             $query->where('recipientName', 'like', '%' . $request->cname . '%');
    //         } elseif ($request->address) {
    //             $query->where('recipientAddress', $request->address);
    //         } elseif ($request->startDate && $request->endDate) {
    //             $startDate = Carbon::parse($request->startDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->endDate)->endOfDay();
    //             $query->whereBetween('created_at', [$startDate, $endDate]);
    //         } elseif ($request->upstartDate && $request->upendDate) {
    //             $startDate = Carbon::parse($request->upstartDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->upendDate)->endOfDay();
    //             $query->whereBetween('updated_at', [$startDate, $endDate]);
    //         } elseif ($request->UpStatusArray != null) {
    //             $query->whereIn('status', $request->UpStatusArray);
    //         }

    //     } else {

    //         $slug       = $slug;
    //         $parceltype = Parceltype::where('slug', $slug)->first();
    //         $query      = \App\Parcel::select('*')
    //             ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
    //             ->where('merchantId', Session::get('merchantId'))
    //             ->where('status', $parceltype->id)
    //             ->whereMonth('parcels.updated_at', now()->month)
    //             ->whereYear('parcels.updated_at', now()->year)
    //             ->orderBy('updated_at', 'DESC');
    //         if ($request->trackId) {
    //             $query->where('trackingCode', $request->trackId);
    //         } elseif ($request->phoneNumber) {
    //             $query->where('recipientPhone', $request->phoneNumber);
    //         } elseif ($request->cname) {
    //             $query->where('recipientName', 'like', '%' . $request->cname . '%');
    //         } elseif ($request->address) {
    //             $query->where('recipientAddress', $request->address);
    //         } elseif ($request->startDate && $request->endDate) {
    //             $startDate = Carbon::parse($request->startDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->endDate)->endOfDay();
    //             $query->whereBetween('created_at', [$startDate, $endDate]);
    //         } elseif ($request->upstartDate && $request->upendDate) {
    //             $startDate = Carbon::parse($request->upstartDate)->startOfDay();
    //             $endDate   = Carbon::parse($request->upendDate)->endOfDay();
    //             $query->whereBetween('updated_at', [$startDate, $endDate]);
    //         } elseif ($request->UpStatusArray != null) {
    //             $query->whereIn('status', $request->UpStatusArray);
    //         }

    //     }
    //     $count     = $query->count();
    //     $allparcel = $query->offset($start)->limit($length)->get();

    //     $aparceltypes = Parceltype::limit(3)->get();
    //     //   return $allparcel;
    //     // Data table
    //     $data = [
    //         'draw'            => $draw,
    //         'recordsTotal'    => $count,
    //         'recordsFiltered' => $count,
    //         'data'            => [],
    //     ];

    //     foreach ($allparcel as $key => $value) {
    //         $deliverymanInfo = Deliveryman::find($value->deliverymanId);
    //         $merchantInfo    = Merchant::find($value->merchantId);
    //         $parcelstatus    = Parceltype::find($value->status);
    //         $merchantDetails = $value->getMerchantOrSenderDetails();

    //         $datavalue[0] = $value->trackingCode;
    //         $datavalue[1] = '<ul class="action_buttons cust-action-btn"><li class="m-1"><button class="btn btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li><li class="m-1"><a href="' . url('merchant/parcel/in-details/' . $value->id) . '" class="btn btn-secondary px-3" title="Track"><i class="fa fa-info"></i></a></li>';
    //         if ($value->status == 1) {
    //             $datavalue[1] .= '<li class="m-1"><a href="' . url('merchant/parcel/edit/' . $value->id) . '" class="btn btn-danger"><i class="fa fa-edit"></i></a></li>';
    //         }
    //         $datavalue[1] .= '<li class="m-1"><a class="btn btn-primary" href="' . url('merchant/parcel/invoice/' . $value->id) . '"  target="_blank" title="Invoice"><i class="fas fa-list"></i></a></li>';
    //         $datavalue[2] = date('F d, Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
    //         $datavalue[3] = $value->recipientName;
    //         $datavalue[4] = $value->recipientPhone;
    //         $parcelstatus = \App\Parceltype::find($value->status);

    //         if ($parcelstatus != null) {
    //             $datavalue[5] = $parcelstatus->title;
    //         } else {
    //             $datavalue[5] = '';
    //         }
    //         $deliverymanInfo = \App\Deliveryman::find($value->deliverymanId);
    //         if ($value->deliverymanId) {
    //             $datavalue[6] = $deliverymanInfo->name;
    //         } else {
    //             $datavalue[6] = 'Not Asign';
    //         }
    //         $datavalue[7]  = number_format($value->cod, 2);
    //         $datavalue[8]  = number_format($value->package_value, 2);
    //         $datavalue[9]  = number_format($value->deliveryCharge, 2);
    //         $datavalue[10] = number_format($value->codCharge, 2);

    //         $datavalue[11] = number_format($value->tax, 2);
    //         $datavalue[12] = number_format($value->insurance, 2);
    //         $datavalue[13] = number_format(
    //             ($value->cod ?? 0) - (($value->deliveryCharge ?? 0) + ($value->codCharge ?? 0) + ($value->tax ?? 0) + ($value->insurance ?? 0)),
    //             2
    //         );

    //         $datavalue[14] = date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
    //         if ($value->merchantpayStatus == null) {
    //             $datavalue[15] = '<button class="btn " style="background-color: #7C7C7C; color:white"> NULL </button>';
    //         } elseif ($value->merchantpayStatus == 0) {
    //             $datavalue[15] = '<button class="btn " style="background-color: #28A745; color:white"> Processing</button>';
    //         } else {
    //             $datavalue[15] = '<button class="btn " style="background-color: #28A745; color:white"> Paid </button>';
    //         }
    //         $datavalue[16] = $value->id;
    //         $parcelnote    = \App\Parcelnote::where('parcelId', $value->id)->orderBy('id', 'DESC')->first();
    //         if (! empty($parcelnote)) {
    //             $datavalue[17] = $parcelnote->note;
    //         } else {
    //             $datavalue[17] = '';
    //         }

    //         array_push($data['data'], $datavalue);

    //     }
    //     return $data;
    // }
    // ** remove this after developement of way bill
     public function get_parcel_data_month($slug, Request $request)
    {
        $parceltype = Parceltype::where('slug', $slug)->first();
        // Datatable
        $start = 0;
        if (isset($request->start)) {
            $start = $request->start;
        }
        $draw = 1;
        if (isset($request->draw)) {
            $draw = $request->draw;
        }
        $length = 10;
        if (isset($request->length)) {
            $length = $request->length;
        }

        $filter = $request->filter_id;

        if ($slug == 'all') {
            $query = \App\Parcel::select('*')
                ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
                ->where('merchantId', Session::get('merchantId'))
                ->whereMonth('parcels.updated_at', now())
                ->orderBy('updated_at', 'DESC');

            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            } elseif ($request->phoneNumber) {
                $query->where('recipientPhone', $request->phoneNumber);
            } elseif ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            } elseif ($request->address) {
                $query->where('recipientAddress', $request->address);
            } elseif ($request->startDate && $request->endDate) {
                $startDate = Carbon::parse($request->startDate)->startOfDay();
                $endDate   = Carbon::parse($request->endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($request->upstartDate && $request->upendDate) {
                $startDate = Carbon::parse($request->upstartDate)->startOfDay();
                $endDate   = Carbon::parse($request->upendDate)->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            } elseif ($request->UpStatusArray != null) {
                $query->whereIn('status', $request->UpStatusArray);
            }

        } else {

            $slug       = $slug;
            $parceltype = Parceltype::where('slug', $slug)->first();
            $query      = \App\Parcel::select('*')
                ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
                ->where('merchantId', Session::get('merchantId'))
                ->where('status', $parceltype->id)
                ->whereMonth('parcels.updated_at', now()->month)
                ->whereYear('parcels.updated_at', now()->year)
                ->orderBy('updated_at', 'DESC');
            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            } elseif ($request->phoneNumber) {
                $query->where('recipientPhone', $request->phoneNumber);
            } elseif ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            } elseif ($request->address) {
                $query->where('recipientAddress', $request->address);
            } elseif ($request->startDate && $request->endDate) {
                $startDate = Carbon::parse($request->startDate)->startOfDay();
                $endDate   = Carbon::parse($request->endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($request->upstartDate && $request->upendDate) {
                $startDate = Carbon::parse($request->upstartDate)->startOfDay();
                $endDate   = Carbon::parse($request->upendDate)->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            } elseif ($request->UpStatusArray != null) {
                $query->whereIn('status', $request->UpStatusArray);
            }

        }
        $count     = $query->count();
        $allparcel = $query->offset($start)->limit($length)->get();

        $aparceltypes = Parceltype::limit(3)->get();
        //   return $allparcel;
        // Data table
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($allparcel as $key => $value) {
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $merchantInfo    = Merchant::find($value->merchantId);
            $parcelstatus    = Parceltype::find($value->status);
            $merchantDetails = $value->getMerchantOrSenderDetails();


            
            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" data-parcel_status_update_sl="' . $parcelstatus->sl . '" name="parcel_id[]" form="myform"></form>';
            $datavalue[1] = $value->trackingCode;
            $datavalue[2] = '<ul class="action_buttons cust-action-btn"><li class="m-1"><button class="btn btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li><li class="m-1"><a href="' . url('merchant/parcel/in-details/' . $value->id) . '" class="btn btn-secondary px-3" title="Track"><i class="fa fa-info"></i></a></li>';
            if ($value->status == 1) {
                $datavalue[2] .= '<li class="m-1"><a href="' . url('merchant/parcel/edit/' . $value->id) . '" class="btn btn-danger"><i class="fa fa-edit"></i></a></li>';
            }
            $datavalue[2] .= '<li class="m-1"><a class="btn btn-primary" href="' . url('merchant/parcel/invoice/' . $value->id) . '"  target="_blank" title="Invoice"><i class="fas fa-list"></i></a></li>';
            $datavalue[3] = date('F d, Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            $datavalue[4] = $value->recipientName;
            $datavalue[5] = $value->recipientPhone;
            $parcelstatus = \App\Parceltype::find($value->status);

            if ($parcelstatus != null) {
                $datavalue[6] = $parcelstatus->title;
            } else {
                $datavalue[6] = '';
            }
            $deliverymanInfo = \App\Deliveryman::find($value->deliverymanId);
            if ($value->deliverymanId) {
                $datavalue[7] = $deliverymanInfo->name;
            } else {
                $datavalue[7] = 'Not Asign';
            }
            $datavalue[8]  = number_format($value->cod, 2);
            $datavalue[9]  = number_format($value->package_value, 2);
            $datavalue[10]  = number_format($value->deliveryCharge, 2);
            $datavalue[11] = number_format($value->codCharge, 2);

            $datavalue[12] = number_format($value->tax, 2);
            $datavalue[13] = number_format($value->insurance, 2);
            $datavalue[14] = number_format(
                ($value->cod ?? 0) - (($value->deliveryCharge ?? 0) + ($value->codCharge ?? 0) + ($value->tax ?? 0) + ($value->insurance ?? 0)),
                2
            );

            $datavalue[15] = date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
            if ($value->merchantpayStatus == null) {
                $datavalue[16] = '<button class="btn " style="background-color: #7C7C7C; color:white"> NULL </button>';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[16] = '<button class="btn " style="background-color: #28A745; color:white"> Processing</button>';
            } else {
                $datavalue[16] = '<button class="btn " style="background-color: #28A745; color:white"> Paid </button>';
            }
            $datavalue[17] = $value->id;
            $parcelnote    = \App\Parcelnote::where('parcelId', $value->id)->orderBy('id', 'DESC')->first();
            if (! empty($parcelnote)) {
                $datavalue[18] = $parcelnote->note;
            } else {
                $datavalue[18] = '';
            }

            array_push($data['data'], $datavalue);

        }
        return $data;
    }
    public function parceldetails($id)
    {
        $query = \App\Parcel::select('*')
            ->where(['merchantId' => Session::get('merchantId'), 'id' => $id])
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderByDesc('id');
        $parceldetails = $query->first();

        $trackInfos = Parcelnote::where('parcelId', $id)->orderBy('id', 'ASC')->with('notes')->get();

        return view('frontEnd.layouts.pages.merchant.parceldetails', compact('parceldetails', 'trackInfos'));
    }

    public function invoice($id)
    {
        $query = \App\Parcel::select('*')
            ->where(['merchantId' => Session::get('merchantId'), 'id' => $id])
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderByDesc('id');
        $show_data = $query->first();

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.merchant.invoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');

            return redirect()->back();
        }

    }

    public function parceledit($id)
    {
        $edit_data = Parcel::where(['merchantId' => Session::get('merchantId'), 'id' => $id])
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'deliverymen', 'agent', 'parceltype'])
            ->first();
        $merchantDetails = Merchant::find(Session::get('merchantId'));
        $merchantSubsPlan = MerchantSubscriptions::with('plan')->where('merchant_id', Session::get('merchantId'))->where('is_active', 1)->first();


        if ($edit_data != null) {
            $ordertype = Deliverycharge::find($edit_data->orderType);
            $codcharge = Codcharge::find($edit_data->codType);

            $areas = Nearestzone::where('status', 1)->get();
            Session::put('codpay', $edit_data->cod);
            Session::put('pcodecharge', $edit_data->codCharge);
            Session::put('pdeliverycharge', $edit_data->deliveryCharge);
            Session::put('mtax', $edit_data->tax);
            Session::put('minsurance', $edit_data->insurance);

            return view('frontEnd.layouts.pages.merchant.parceledit', compact('ordertype', 'codcharge', 'edit_data', 'merchantDetails', 'merchantSubsPlan'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');

            return redirect()->back();
        }

    }

    public function singleservice(Request $request)
    {
        $data = [
            'contact_mail' => 'info@8809612440738.com.bd',
            'address'      => $request->address,
            'area'         => $request->area,
            'note'         => $request->note,
            'estimate'     => $request->estimate,
        ];
        $send = Mail::send('frontEnd.emails.singleservice', $data, function ($textmsg) use ($data) {
            $textmsg->to($data['contact_mail']);
            $textmsg->subject('A Single Service Request');
        });
        Toastr::success('Success!', 'Thanks! your  request send successfully');

        return redirect()->back();
    }

    public function payments()
    {
        /*$merchantInvoice =DB::table('merchantpayments')
        ->join('parcels','parcels.id','merchantpayments.parcelId')
        ->selectRaw('count(merchantpayments.id) as total_parcel,sum(parcels.merchantPaid) as total, merchantpayments.updated_at, merchantpayments.parcelId')
        ->groupBy('merchantpayments.updated_at')
        ->where('merchantpayments.merchantId', Session::get('merchantId'))
        ->orderBy('updated_at', 'DESC')
        ->get();*/

        $merchantInvoice = DB::table('merchantpayments')
            ->join('parcels', 'parcels.id', 'merchantpayments.parcelId')
            ->where('merchantpayments.merchantId', Session::get('merchantId'))
            ->groupBy(['updated_at'])
            ->selectRaw('DATE(merchantpayments.updated_at) as date, count(merchantpayments.id) as total_parcel,sum(parcels.merchantPaid) as total, merchantpayments.updated_at, merchantpayments.parcelId, merchantpayments.merchantId')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('frontEnd.layouts.pages.merchant.payments', compact('merchantInvoice'));
    }

    public function inovicedetails(Request $request)
    {
        $update   = $request->update;
        $parcelId = Merchantpayment::where('updated_at', $update)
            ->where('merchantId', Session::get('merchantId'))
            ->pluck('parcelId')
            ->toArray();
        $parcels      = DB::table('parcels')->whereIn('id', $parcelId)->get();
        $merchantInfo = Merchant::find($parcels->first()->merchantId);
        return view('frontEnd.layouts.pages.merchant.inovicedetails', compact('parcels', 'merchantInfo'));
    }

    public function returnpayments()
    {

        $merchantInvoice = DB::table('merchantreturnpayments')
            ->join('parcels', 'parcels.id', 'merchantreturnpayments.parcelId')
            ->where('merchantreturnpayments.merchantId', Session::get('merchantId'))
            ->groupBy(['updated_at'])
            ->selectRaw('DATE(merchantreturnpayments.updated_at) as date, count(merchantreturnpayments.id) as total_parcel,sum(parcels.deliveryCharge + parcels.tax + parcels.insurance) as total, merchantreturnpayments.updated_at, merchantreturnpayments.parcelId, merchantreturnpayments.merchantId')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('frontEnd.layouts.pages.merchant.returnpayments', compact('merchantInvoice'));
    }

    public function returninovicedetails(Request $request)
    {
        $update   = $request->update;
        $parcelId = Merchantreturnpayment::where('updated_at', $update)
            ->where('merchantId', Session::get('merchantId'))
            ->pluck('parcelId')
            ->toArray();
        $parcels  = Parcel::whereIn('id', $parcelId)->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->get();
        $marchent = Merchant::find($parcels->first()->merchantId);
        return view('backEnd.parcel.invoice_return_to_merchat', compact('parcels', 'marchent', 'update'));
        // return view('frontEnd.layouts.pages.merchant.returninovicedetails', compact('parcels', 'merchantInfo'));
    }

    public function passreset()
    {
        return view('frontEnd.layouts.pages.passreset');
    }

    public function Oldpassfromreset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);
        $validMerchant = Merchant::Where('emailAddress', $request->phoneNumber)
            ->first();

        if ($validMerchant) {

            $verifyToken                  = rand(111111, 999999);
            $validMerchant->passwordReset = $verifyToken;
            $validMerchant->save();
            Session::put('resetCustomerId', $validMerchant->id);

            //  $data = array(

            //  'contact_mail' => $validMerchant->phoneNumber,

            //  'verifyToken' => $verifyToken,

            // );

            // $send = Mail::send('frontEnd.emails.passwordreset', $data, function($textmsg) use ($data){

            //  $textmsg->from('info@8809612440738.com.bd');

            //  $textmsg->to($data['contact_mail']);

            //  $textmsg->subject('Forget password token');

            // });

            //   $url = "http://premium.mdlsms.com/smsapi";

            //   $data = [

            //     "api_key" => "C20005455f867568bd8c02.20968541",

            //     "type" => "text",

            //     "contacts" => $validMerchant->phoneNumber,

            //     "senderid" => "8809612440738",

            //     "msg" => "Dear $validMerchant->firstName, \r\n Your password reset token is $verifyToken. Enjoy our services. If any query call us 01711132240\r\nRegards\r\nZuri Express ",

            //   ];

            //   $ch = curl_init();

            //   curl_setopt($ch, CURLOPT_URL, $url);

            //   curl_setopt($ch, CURLOPT_POST, 1);

            //   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            //   $response = curl_exec($ch);
            //   curl_close($ch);

            $url  = "https://sms.solutionsclan.com/api/sms/send";
            $data = [
                "apiKey"         => "A00003133467cc6-219a-4dfd-882e-0bcf8836ebc3",
                "contactNumbers" => $request->phoneNumber,
                "senderId"       => "8809612440632",
                "textBody"       => "Dear $validMerchant->firstName, \r\n Your password reset token is $verifyToken. Enjoy our services. If any query call us 01711132240\r\nRegards\r\nZuri Express ",
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($ch);
            echo "$response";
            curl_close($ch);

            return redirect('/merchant/resetpassword/verify');
        } else {
            Toastr::error('Sorry! You have no account', 'warning!');

            return redirect()->back();
        }

    }

    public function passfromreset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);
        $validMerchant = Merchant::Where('emailAddress', $request->email)
            ->first();

        if ($validMerchant) {

            $verifyToken                  = rand(111111, 999999);
            $validMerchant->passwordReset = $verifyToken;
            $validMerchant->save();
            Session::put('resetCustomerId', $validMerchant->id);

            try {
                Mail::to($validMerchant->emailAddress)->send(new MerchantResetPasswordEmail($validMerchant));
            } catch (\Exception $exception) {
                Log::info('Merchant Forget password mail error: ' . $exception->getMessage());
            }

            return redirect('/merchant/resetpassword/verify');
        } else {
            Toastr::error('Sorry! You have no account', 'warning!');

            return redirect()->back();
        }

    }

    public function resetpasswordverify()
    {

        if (Session::get('resetCustomerId')) {
            return view('frontEnd.layouts.pages.passwordresetverify');
        } else {
            Toastr::error('Sorry! Your process something wrong', 'warning!');

            return redirect('forget/password');
        }

    }

    public function saveResetPassword(Request $request)
    {
        $validMerchant = Merchant::find(Session::get('resetCustomerId'));

        if ($validMerchant->passwordReset == $request->verifyPin) {
            $validMerchant->password      = bcrypt(request('newPassword'));
            $validMerchant->passwordReset = null;
            $validMerchant->save();

            Session::forget('resetCustomerId');
            Session::put('merchantId', $validMerchant->id);
            Toastr::success('Wow! Your password reset successfully', 'success!');

            return redirect('/merchant/dashboard');
        } else {
            Toastr::error('Sorry! Your process something wrong', 'warning!');

            return redirect()->back();
        }

    }

    public function parceltrack(Request $request)
    {
        $trackparcel = Parcel::where('trackingCode', 'LIKE', '%' . $request->trackid . "%")
            ->where('merchantId', Session::get('merchantId'))
            ->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->first();

        if ($trackparcel) {
            $trackInfos = Parcelnote::where('parcelId', $trackparcel->id)->orderBy('id', 'ASC')->with('notes')->get();

            return view('frontEnd.layouts.pages.merchant.trackparcel', compact('trackparcel', 'trackInfos'));
        } else {
            return redirect()->back();
        }

    }

    public function import(Request $request)
    {
        Excel::import(new ParcelImport(), request()->file('excel'));
        Toastr::success('Wow! Bulk uploaded', 'success!');

        return redirect()->back();
    }


    public function export(Request $request)
    {
        return Excel::download(new ParcelExport(), 'parcel.xlsx');

    }

    public function index()
    {
        return view('frontEnd.layouts.pages.merchant.changepass');
    }

    public function changepassword(Request $request)
    {
        $this->validate($request, [
            'old_password'          => 'required',
            'new_password'          => 'required',
            'password_confirmation' => 'required_with:new_password|same:new_password|',
        ]);

        $user     = Merchant::find(Session::get('merchantId'));
        $hashPass = $user->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $user->fill([
                'password' => Hash::make($request->new_password),
            ])->save();

            Toastr::success('message', 'Password changed successfully!');

            return back();
        } else {
            Toastr::error('message', 'Old password not match!');

            return back();
        }

    }
     public function PrintSelectedItems(Request $request)
    {
        $parcels = \App\Parcel::whereIn('id', explode(',', $request->query('parcels'))) // Accept comma-separated values
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->get();

        $pdf = new \Mpdf\Mpdf([
            'mode'              => 'utf-8',
                                         // 'format' => [210.0, 148.5], // Use 148.5 for exact half of A4 height
            'format'            => 'A4', // Use 148.5 for exact half of A4 height
            'orientation'       => 'P',
            'margin_top'        => 5,
            'margin_bottom'     => 5,
            'margin_left'       => 5,
            'margin_right'      => 5,
            'default_font_size' => 10,
            'default_font'      => 'dejavusans',
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
            'margin_footer'     => 0,
            'shrink_tables'     => 1,
        ]);

        $view = 'pdf.pdf';
        $html = trim(view($view, ['parcels' => $parcels])->render());
        $pdf->WriteHTML($html);

        $timestamp = Carbon::now()->format('j-M-Y g:i A'); // 12-hour format with AM/PM
        $filename  = "ZiDrop_Waybill_{$timestamp}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, $filename, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment',
        ]);
    }

}
