<?php
namespace App\Http\Controllers\FrontEnd;

use App\Agent;
use App\AgentCommission;
use App\Agentpayment;
use App\Agentpaymentdetail;
use App\Deliverycharge;
use App\Deliveryman;
use App\Exports\AgentParcelExport;
use App\History;
use App\Http\Controllers\Controller;
use App\Mail\ParcelStatusUpdateEmail;
use App\Merchant;
use App\Parcel;
use App\Parcelnote;
use App\Parceltype;
use App\Pickup;
use App\RemainTopup;
use App\RetMerchantHistory;
use App\MerchantSubscriptions;
use App\TransferHistory;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Session;

class AgentController extends Controller
{

// public function __construct()

// {

//     dd(Session::get('agentId'));

//     $agentsss = Agent::find(Session::get('agentId'));

//     dd($agentsss);

//     $Agentdeliverymen = Deliveryman::where('state', $agentsss->state)->where('status', 1)->orderBy('id','ASC')->get();

//     view()->share('Agentdeliverymen', $Agentdeliverymen);
    // }
    public function commission_history($id)
    {
        $agentId = $id;
        return view('frontEnd.layouts.pages.agent.commissionpayments', compact('agentId'));

    }
    public function get_commission_history($id)
    {
        // Base query
        $query = AgentCommission::where('agent_id', $id)->where('pay_status', 1)
            ->where('agent_id', Session::get('agentId'))
            ->with(['agent'])
            ->orderByDesc('id');

        // Pagination variables
        $start  = $request->start ?? 0;
        $draw   = $request->draw ?? 1;
        $length = $request->length ?? 10;

        // Clone query to avoid running twice
        $count     = (clone $query)->count();
        $show_data = $query->offset($start)->limit($length)->get();

        // Manually load computed `parcels` attribute
        $show_data->each(function ($commission) {
            $commission->parcels = $commission->parcels; // This triggers the `getParcelsAttribute()`
        });

        // Prepare DataTable response
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($show_data as $key => $value) {
            $invoiceUrl   = route('agent.payment.invoice_commission_history', $value->id);
            $TotalInvoice = count(explode(',', $value->parcel_ids));

            $datavalue = [
                $key + 1, // Serial number starts from 1
                optional($value->agent)->name,
                number_format($value->agent_commission, 2),
                date('d M Y', strtotime($value->date)) . '<br>' . date("g:i a", strtotime($value->created_at)),
                $TotalInvoice,
                '<td>
                    <ul class="action_buttons cust-action-btn">
                      <li>
                         <a class="btn btn-primary" href="' . $invoiceUrl . '" title="Invoice">
                            <i class="fa fa-eye"></i>
                         </a>
                      </li>
                   </ul>
                </td>',
            ];

            $data['data'][] = $datavalue;
        }

        return response()->json($data);

    }
    public function invoice_commission_history($id)
    {
        // Retrieve AgentCommission by ID and load related agent and parcels
        $AgentCommission = AgentCommission::where('id', $id)
            ->with(['agent']) // Eager load the agent
            ->first();        // Get the first (and only) result by ID

        // Check if the commission exists
        if (! $AgentCommission) {
            return redirect()->back()->with('error', 'Agent Commission not found.');
        }

                                              // Now, you can access the parcels using the `parcels` attribute
        $parcels = $AgentCommission->parcels; // This triggers the `getParcelsAttribute()` method

        // Pass the data to the view
        return view('frontEnd.layouts.pages.agent.invoice_commission', compact('AgentCommission', 'parcels'));
    }
    public function singlestatusupdate(Request $request)
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        if ($request->slug == 'picked_up') {
            $updatedParcels = 0; // Counter for updated parcels
            foreach ($request->parcels as $value) {
                $parcel = Parcel::find($value);

                if ($parcel) {
                    $parcel->status     = 12;
                    $parcel->updated_at = Carbon::now();
                    $parcel->save();

                    // Create Parcelnote
                    $note           = new Parcelnote();
                    $note->parcelId = $value;
                    $note->note     = "PARCEL HAS BEEN PICKED UP FROM DROP-OFF POINT";
                    $note->save();

                    $deliverymanInfo    = Deliveryman::where(['id' => $parcel->deliverymanId])->first();
                    $history            = new History();
                    $history->name      = $parcel->recipientName;
                    $history->parcel_id = $parcel->id;
                    $history->done_by   = $agentsettings->name;
                    $history->status    = 'PICKED UP';
                    $history->note      = 'PARCEL HAS BEEN PICKED UP FROM DROP-OFF POINT';
                    $history->date      = $parcel->updated_at;
                    $history->save();

                    if ($parcel->parcel_source == 'p2p') {
                        $merchantDetails = $parcel->getMerchantOrSenderDetails();
                        try {
                            if (! empty($merchantDetails)) {
                                Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    } else {
                        try {
                            $validMerchant = Merchant::find($parcel->merchantId);
                            if (! empty($validMerchant)) {
                                Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    }

                    $updatedParcels++; // Increment counte

                }
            }
            if ($updatedParcels > 0) {

                return response()->json(['success' => true, 'message' => 'Parcel status updated successfully!']);
            } else {
                return response()->json(['fail' => false, 'message' => 'No parcels updated.']);
            }
        } else {
            return response()->json(['error' => false, 'message' => 'Invalid slug.']);
        }
    }

    public function pickupupdate(Request $request)
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
            $updatedParcels = 0; // Counter for updated parcels
            $parcelIds = explode(',', $request->pu_parcel_id);
            foreach ($parcelIds as $value) {
                $parcel = Parcel::find($value);

                if ($parcel) {
                    $parcel->status     = 12;
                    $parcel->agentId     = $request->agentId;
                    $parcel->updated_at = Carbon::now();
                    $parcel->save();

                    // Create Parcelnote
                    $note           = new Parcelnote();
                    $note->parcelId = $value;
                    $note->note     = "PARCEL HAS BEEN PICKED UP FROM DROP-OFF POINT";
                    $note->save();

                    $deliverymanInfo    = Deliveryman::where(['id' => $parcel->deliverymanId])->first();
                    $history            = new History();
                    $history->name      = $parcel->recipientName;
                    $history->parcel_id = $parcel->id;
                    $history->done_by   = $agentsettings->name;
                    $history->status    = 'PICKED UP';
                    $history->note      = 'PARCEL HAS BEEN PICKED UP FROM DROP-OFF POINT';
                    $history->date      = $parcel->updated_at;
                    $history->save();

                    if ($parcel->parcel_source == 'p2p') {
                        $merchantDetails = $parcel->getMerchantOrSenderDetails();
                        try {
                            if (! empty($merchantDetails)) {
                                Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    } else {
                        try {
                            $validMerchant = Merchant::find($parcel->merchantId);
                            if (! empty($validMerchant)) {
                                Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    }

                    $updatedParcels++; // Increment counte

                }
            }
            if ($updatedParcels > 0) {
                Toastr::success('success', 'Parcel status updated successfully!');

                return redirect()->back();

            } else {
                Toastr::error('Opps!', 'No parcels updated.');
                return redirect()->back();
            }
    }
    public function returntomerchant(Request $request)
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;

        $merchant_ids      = []; // Array to store merchant IDs
        $parcelsByMerchant = []; // Array to store parcel IDs grouped by merchant

        if ($request->slug == 'return-to-merchant') {
            $updatedParcels = 0; // Counter for updated parcels

            foreach ($request->parcels as $value) {
                $parcel = Parcel::find($value);
                if ($parcel) {
                    $parcel->status     = 8; // 8 for return to merchant
                    $parcel->updated_at = Carbon::now();
                    $parcel->save();

                    // Create Parcelnote
                    $note           = new Parcelnote();
                    $note->parcelId = $value;
                    $note->note     = "PARCEL HAS BEEN RETURNED TO MERCHANT";
                    $note->save();

                    // Create History
                    $history            = new History();
                    $history->name      = $parcel->recipientName;
                    $history->parcel_id = $parcel->id;
                    $history->done_by   = $agentsettings->name;
                    $history->status    = 'TRANSFER TO MERCHANT';
                    $history->note      = "PARCEL HAS BEEN RETURNED TO MERCHANT";
                    $history->date      = $parcel->updated_at;
                    $history->save();

                    // Collect merchant ID and parcel IDs
                    if (! in_array($parcel->merchantId, $merchant_ids)) {
                        $merchant_ids[] = $parcel->merchantId;
                    }
                    $parcelsByMerchant[$parcel->merchantId][] = $parcel->id;

                    // Email
                    if ($parcel->parcel_source == 'p2p') {
                        $merchantDetails = $parcel->getMerchantOrSenderDetails();
                        try {
                            if (! empty($merchantDetails)) {
                                Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    } else {

                        // Send email notification
                        try {
                            $validMerchant = Merchant::find($parcel->merchantId);
                            if (! empty($validMerchant)) {
                                Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                            }
                        } catch (\Exception $exception) {
                            Log::info('Parcel status update mail error: ' . $exception->getMessage());
                        }
                    }

                    $updatedParcels++; // Increment counter
                }
            }
            // Generate Transfer History for each merchant
            $batchnumberMake = 'BAT' . mt_rand(1111111111, 9999999999);
            foreach ($merchant_ids as $merchant_id) {
                $parcelIdsString = implode(',', $parcelsByMerchant[$merchant_id]);

                $transferHistory                = new RetMerchantHistory();
                $transferHistory->parcel_ids    = $parcelIdsString;
                $transferHistory->merchant_id   = $merchant_id;
                $transferHistory->name          = 'Return To Merchant';
                $transferHistory->transfer_type = 'return';
                $transferHistory->done_by       = $agentsettings->name;
                $transferHistory->status        = 'Success';
                $transferHistory->note          = 'Return To Merchant From';
                $transferHistory->date          = now();
                $transferHistory->transfer_by   = 'Agent';
                $transferHistory->batchnumber   = $batchnumberMake;
                $transferHistory->origin_hub_id = $agentsettings->id;
                $transferHistory->created_by    = $agentsettings->id;
                $transferHistory->save();
            }

            if ($updatedParcels > 0) {
                return response()->json(['success' => true, 'message' => 'The Parcel Return To Merchant']);
            } else {
                return response()->json(['fail' => false, 'message' => 'No parcels updated.']);
            }
        } else {
            return response()->json(['error' => false, 'message' => 'Invalid slug.']);
        }
    }

    public function view()
    {
        $id        = Session::get('agentId');
        $agentInfo = Agent::find($id);
        $parcels   = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->join('agents', 'parcels.agentId', '=', 'agents.id')
            ->where('parcels.agentId', $id)
            ->orderBy('parcels.id', 'DESC')
            ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
            ->get();
        $totalamount = Parcel::where(['agentId' => $id, 'status' => 4])
            ->sum('merchantDue');
        $unpaidamount = Parcel::where(['agentId' => $id, 'status' => 4])
            ->sum('merchantDue');

        return view('frontEnd.layouts.pages.agent.view', compact('agentInfo', 'parcels', 'totalamount', 'unpaidamount'));
    }

    public function loginform()
    {
        return view('frontEnd.layouts.pages.agent.login');
    }
    public function track(Request $request)
    {
        $trackparcel = Parcel::where('trackingCode', 'LIKE', '%' . $request->trackid . "%")->where('agentId', Session::get('agentId'))->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->first();

        if ($trackparcel) {
            $trackInfos = Parcelnote::where('parcelId', $trackparcel->id)->orderBy('id', 'ASC')->with('notes')->get();

            return view('frontEnd.layouts.pages.agent.trackparcel', compact('trackparcel', 'trackInfos'));
        } else {
            return redirect()->back();
        }

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);
        $checkAuth = Agent::where('email', $request->email)
            ->first();

        if ($checkAuth) {

            if ($checkAuth->status == 0) {
                Toastr::warning('warning', 'Opps! your account has been suspends');

                return redirect()->back();
            } else {

                if (password_verify($request->password, $checkAuth->password)) {
                    $agentId = $checkAuth->id;
                    Session::put('agentId', $agentId);
                    Toastr::success('success', 'Thanks , You are login successfully');

                    return redirect('/agent/dashboard');

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

    public function dashboard()
    {
        $agent          = Agent::find(Session::get('agentId'));
        $totalparcel    = Parcel::where(['agentId' => Session::get('agentId')])->count();
        $totaldelivery  = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 4])->count();
        $totalhold      = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 5])->count();
        $totalcancel    = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 9])->count();
        $returntohub    = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 7])->count();
        $returnmerchant = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 8])->count();
        $admindue       = Parcel::where('agentId', Session::get('agentId'))
            ->whereIn('status', [6, 4])
            ->whereNull('agentPaystatus')
            ->sum('agentAmount');

        // $pending          = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 1])->count();
        $pending = Parcel::where('status', 1)
    ->where(function ($q) {
        $q->where('status', '!=', 1)
          ->where('agentId', Session::get('agentId'))
          ->orWhere('status', 1);
    })
    ->count();


        $pickup           = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 12])->count();
        $intransit        = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 2])->count();
        $arriveathub      = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 10])->count();
        $outfordevilery   = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 3])->count();
        $delivered        = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 4])->count();
        $partialdelivery  = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 6])->count();
        $disputedpackages = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 5])->count();
        $returtohub       = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 7])->count();
        // get where status 4 and 6
        $totalamount = Parcel::where('agentId', Session::get('agentId'))->whereIn('status', [6, 4])
            ->sum('cod');

        // Pi Chart
        $months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December",
        ];

        // Fetch delivered parcels data with full month and year
        $deliveredParcelsRaw = Parcel::selectRaw("DATE_FORMAT(updated_at, '%M %Y') as month_year, COUNT(*) as count")
            ->where('agentId', $agent->id)
            ->whereIn('status', [4, 6]) // Adjust the status as per requirement
            ->whereYear('updated_at', date('Y'))
            ->groupBy('month_year')
            ->orderByRaw("MONTH(updated_at)") // Ensure correct order
            ->pluck('count', 'month_year');   // Fetch as key-value pair (month_year => count)

        // Fetch pickup parcels data with full month and year
        $pickupParcelsRaw = Parcel::selectRaw("DATE_FORMAT(created_at, '%M %Y') as month_year, COUNT(*) as count")
            ->where('agentId', $agent->id)
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
        // $data['deliveredParcels'] = $deliveredParcels;
        // $data['pickupParcels'] = $pickupParcels;

        return view('frontEnd.layouts.pages.agent.dashboard', compact('totalparcel', 'totalhold', 'totalcancel', 'returntohub', 'returnmerchant', 'totalamount', 'pending', 'intransit', 'arriveathub', 'outfordevilery', 'delivered', 'partialdelivery', 'disputedpackages', 'returtohub', 'admindue', 'pickup', 'agent', 'deliveredParcels', 'pickupParcels'));
    }

    public function parcels(Request $request)
    {
        session()->forget('OLDTrackingCodesQRscans_agents');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans_agents', $OLDTrackingCodesQRscans);
        $filter = $request->filter_id;

        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->where('parcels.agentId', Session::get('agentId'))
            ->orderBy('id', 'DESC');

        if ($request->trackId) {
            $query->where('trackingCode', $request->trackId);
        } elseif ($request->merchantId) {
            $query->where('merchantId', $request->merchantId);
        } elseif ($request->cname) {
            $query->where('recipientName', 'like', '%' . $request->cname . '%');
        } elseif ($request->address) {
            $query->where('recipientAddress', $request->address);
        } elseif ($request->phoneNumber) {
            $query->where('recipientPhone', $request->phoneNumber);
        } elseif ($request->startDate && $request->endDate) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate   = Carbon::parse($request->endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $count     = $query->count();
        $allparcel = $query->paginate(10);

        $aparceltypes     = Parceltype::limit(3)->get();
        $agentsss         = Agent::find(Session::get('agentId'));
        $Agentdeliverymen = Deliveryman::where('cities_id', $agentsss->cities_id)->where('status', 1)->orderBy('id', 'ASC')->get();
        $slug             = 'all';
        $parceltype       = [];
        //   return $allparcel;

        return view('frontEnd.layouts.pages.agent.parcels', compact('allparcel', 'aparceltypes', 'slug', 'Agentdeliverymen', 'parceltype'));
    }

    public function requestpaid(Request $request)
    {

        $payments = Agentpayment::where('agentId', Session::get('agentId'))->orderBy('id', 'DESC')->get();

        $due = Parcel::where('agentId', Session::get('agentId'))->whereIn('status', [6, 4])->whereNull('agentPaystatus')->sum('agentAmount');

        return view('frontEnd.layouts.pages.agent.requestpaid', compact('payments', 'due'));
    }

    public function requestpaidPost(Request $request)
    {
        $payment          = new Agentpayment();
        $payment->agentId = Session::get('agentId');
        $payment->due     = $request->due;
        $payment->status  = 0;
        $payment->save();

        $parcels = Parcel::where('agentId', Session::get('agentId'))->whereIn('status', [6, 4])->whereNull('agentPaystatus')->where('agentAmount', '>', 0)->get();

        foreach ($parcels as $value) {
            $parcel                 = Parcel::where('id', $value->id)->first();
            $parcel->agentPaystatus = 0;
            $parcel->timestamps     = false;
            $parcel->save();
            $parcel->timestamps = true;

            $paymentdetail            = new Agentpaymentdetail();
            $paymentdetail->parcelId  = $parcel->id;
            $paymentdetail->paymentId = $payment->id;
            $paymentdetail->due       = $parcel->agentAmount;
            $paymentdetail->save();
        }

        Toastr::success('message', 'Payment request has been sent! Wait for the Admins Approval');

        return redirect()->back();
    }

    public function invoicedetails(Request $request)
    {

        $payment = Agentpayment::where('id', $request->paymentId)->first();

        $parcelId = Agentpaymentdetail::where('paymentId', $request->paymentId)
            ->pluck('parcelId')
            ->toArray();
        $parcels = DB::table('parcels')->whereIn('id', $parcelId)->get();

        $agentInfo = Agent::find(Session::get('agentId'));

        return view('frontEnd.layouts.pages.agent.paymentinvoice', compact('parcels', 'agentInfo', 'payment'));
    }

    public function get_parcel_data(Request $request, $slug)
    {
        session()->forget('OLDTrackingCodesQRscans_agents');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans_agents', $OLDTrackingCodesQRscans);
        // dd($request->all());
        session()->forget('OLDTrackingCode');
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

        $length = 50;

        if (isset($request->length)) {
            $length = $request->length;
        }
        
        $filter = $request->filter_id;
        
        if ($slug == 'all') {

            $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype']);
            

            // $query = \App\Parcel::select('*')
            //     ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            //     ->where('agentId', Session::get('agentId'))
            //     ->orderBy('updated_at', 'DESC');

            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            } 
            if ($request->merchantId) {
                $query->where('merchantId', $request->merchantId);
            } 
            if ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            } 
            if ($request->address) {
                $query->where('recipientAddress', $request->address);
            } 
            if ($request->phoneNumber) {
                $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
                $query->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
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


            // ✅ Finally, apply agent filter ONLY if parceltype_id != 1
            $query->where(function ($q) {
                $q->where('status', '!=', 1)
                ->where('agentId', Session::get('agentId'))
                ->orWhere('status', 1); // no agent filter if type is 1
            });


            $query->orderBy('updated_at', 'DESC');

        } else {
            $slug       = $slug;
            $parceltype = Parceltype::where('slug', $slug)->first();


            $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->where('status', $parceltype->id);

            if ($parceltype->id != 1) {
                $query->where('agentId', Session::get('agentId'));
            }
            
            // $query = \App\Parcel::select('*')
            //     ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            //     ->where('agentId', Session::get('agentId'))
            //     ->where('status', $parceltype->id)
            //     ->orderBy('updated_at', 'DESC');
                
                // Clean phoneNumber
                // dd(\App\Parcel::where('agentId', Session::get('agentId'))->where('recipientPhone', '08023205058')->get());

            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            }
            
            if ($request->merchantId) {
                $query->where('merchantId', $request->merchantId);
            }
            
            if ($request->cname) {
                $query->where('recipientName', 'like', '%' . $request->cname . '%');
            }
            
            if ($request->address) {
                $query->where('recipientAddress', 'like', '%' . $request->address . '%');
            }
            
            if ($request->phoneNumber) {
                $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
                $query->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
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

             $query->orderBy('updated_at', 'DESC');
            // dd(\App\Parcel::where('recipientPhone', 'like', '%05454654654%')->first());
        }

        $count     = $query->count();
        $allparcel = $query->offset($start)->limit($length)->get();

        $aparceltypes = Parceltype::limit(3)->get();
        $data         = [
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

            //   $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" name="parcel_id[]" form="myform"> </form>';
            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" data-parcel_status_update_sl="' . $parcelstatus->sl . '" name="parcel_id[]" form="myform"></form>';
            $datavalue[1] = $value->trackingCode;
            // if($value->parcel_source == 'p2p'){
            //     $datavalue[2] = '<ul><li class="m-1"><button class="btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $value->p2pParcel->sender_name . '" data-phonenumber = "' . $value->p2pParcel->sender_mobile  .  '" data-type="'. 'p2p' . '" data-emailaddress = "' . $value->p2pParcel->sender_email . '" data-companyname = "' . 'P2P'  . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) .'" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }else{
            $datavalue[2] = '<ul class="action_buttons cust-action-btn"><li class="m-1"><button class="btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }

            $datavalue[2] .= '<li class="m-1"><button class="btn edit_icon" id="merchantParcelh" data-id="' . $value->id . '" title="History"><i class="fas fa-history"></i></button></li>';
   

            if ($value->status == 1) {
                $datavalue[2] .= '<li class="m-1"><a href="' . route('agent.parcel-edit', $value->id) . '" class="btn btn-primary " title="Edit"><i class="fas fa-edit"></i></a></li>';

            }
            if ($value->status != 8) {
                $datavalue[2] .= '<li class="m-1"><button class="btn-danger" href="#" id="sUpdateModal" title="Action" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"data-parcel_status_update_sl="' . $parcelstatus->sl . '"><i class="fa fa-sync-alt"></i></button></li>';
            }

            $datavalue[2] .= '<li class="m-1"><a class="btn btn-primary " href="' . url('agent/parcel/invoice/' . $value->id) . '" target="_blank"  title="Invoice"><i class="fas fa-list"></i></a></li>';

            $datavalue[2] .= '</ul>';

            $datavalue[3] = date('d M Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            if ($value->parcel_source == 'p2p') {
                $datavalue[4] = 'P2P';
            } else {
                $datavalue[4] = $merchantDetails->companyName;
            }
            $datavalue[5] = $value->recipientName;
            $datavalue[6] = $value->recipientPhone;
            if ($value->deliverymanId) {

                $datavalue[7] = $deliverymanInfo->name;
            } else {
                $datavalue[7] = '<button class="btn btn-primary" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '">Asign</button>';
            }

            $datavalue[8]  = $parcelstatus->title;
            $datavalue[9]  = number_format($value->cod, 2);
            $datavalue[10] = number_format($value->deliveryCharge, 2);
            $datavalue[11] = number_format($value->tax, 2);
            $datavalue[12] = number_format($value->insurance, 2);
            $datavalue[13] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);
            $datavalue[14] = date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
            if ($value->merchantpayStatus == null) {
                $datavalue[15] = 'NULL';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[15] = 'Processing';
            } else {
                $datavalue[15] = 'Paid';
            }

            $parcelnote = Parcelnote::where('parcelId', $value->id)
                ->orderBy('id', 'DESC')
                ->first();
            $datavalue[16] = $merchantInfo->id ?? 'P2P';
            if (! empty($parcelnote)) {
                $datavalue[17] = $parcelnote->note;
            } else {
                $datavalue[17] = '';
            }

            array_push($data['data'], $datavalue);
        }

        return $data;
    }

    public function parcelstatus($slug)
    {

        session()->forget('OLDTrackingCodesQRscans_agents');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans_agents', $OLDTrackingCodesQRscans);
        $slug       = $slug;
        $parceltype = Parceltype::where('slug', $slug)->first();

        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->where('agentId', Session::get('agentId'))
            ->where('status', $parceltype->id)
            ->orderBy('id', 'DESC');
        $allparcel = $query->get();

        //dd($allparcel);
        $agentsss         = Agent::find(Session::get('agentId'));
        $Agentdeliverymen = Deliveryman::where('cities_id', $agentsss->cities_id)->where('status', 1)->orderBy('id', 'ASC')->get();
        return view('frontEnd.layouts.pages.agent.parcels', compact('allparcel', 'slug', 'parceltype', 'Agentdeliverymen'));
    }

    public function invoice($id)
    {
        $show_data = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->where('id', $id)
            ->first();

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.agent.invoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');

            return redirect()->back();
        }

    }
    public function p2pinvoice($id)
    {
        $show_data = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->where('agentId', Session::get('agentId'))
            ->where('id', $id)
            ->first();
        // $p2p_parcel = \App\P2pParcels::where('parcel_id', $show_data->id)->first();
        // dd($p2p_parcel);

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.agent.p2pinvoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');

            return redirect()->back();
        }

    }

    public function delivermanasiagn(Request $request)
    {
        $this->validate($request, [
            'deliverymanId' => 'required',
        ]);
        $parcel                = Parcel::find($request->hidden_id);
        $parcel->deliverymanId = $request->deliverymanId;
        $parcel->save();

        //Save to History table

        $deliveryman = Agent::where('id', session('agentId'))->first();

        $history            = new History();
        $history->name      = $parcel->recipientName;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = $deliveryman->name;
        $history->status    = 'DeliveryMan Asign From Agent.';
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

        Toastr::success('message', 'A deliveryman asign successfully!');

        return redirect()->back();
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
        $merchantinfo    = Agent::find($parcel->merchantId);
        $data            = [
            'contact_mail' => $merchantinfo->email,
            'ridername'    => $deliverymanInfo->name,
            'riderphone'   => $deliverymanInfo->phone,
            'codprice'     => $parcel->cod,
            'trackingCode' => $parcel->trackingCode,
        ];
        $send = Mail::send('frontEnd.emails.percelassign', $data, function ($textmsg) use ($data) {
            $textmsg->from('info@aschi.com.bd');
            $textmsg->to($data['contact_mail']);
            $textmsg->subject('Percel Assign Notification');
        });
    }
    public function gettransreportdata(Request $request)
    {
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

        $query = TransferHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'transfer')
        //->whereNotNull('deliveryman_id')
        //->with('originhub', 'destinationhub')
            ->orderBy('id', 'DESC');

        $count    = $query->count();
        $alldatas = $query->offset($start)->limit($length)->get();
        $data     = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($alldatas as $key => $value) {
            $datavalue[0] = $key + 1;
            $datavalue[1] = date('F d, Y', strtotime($value->date));
            $datavalue[2] = $value->created_at->format('g:i A');
            $datavalue[3] = $value->batchnumber;
            $datavalue[4] = $value->sealnumber;
            $datavalue[5] = $value->tagnumber;
            $datavalue[6] = $value->originhub->name;
            $datavalue[7] = $value->destinationhub->name;
            $datavalue[8] = '<a class="btn btn-primary tthViwwButton" href="' . url('/agent/parcel/transferhub/report/' . $value->id) . '" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            array_push($data['data'], $datavalue);
        }

        return $data;
    }
    public function geassigndelreportdata(Request $request)
    {
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

        $query = TransferHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'assigndel')
            ->with('originhub', 'destinationhub', 'deliverymen')
            ->orderBy('id', 'DESC');

        $count    = $query->count();
        $alldatas = $query->offset($start)->limit($length)->get();
        $data     = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($alldatas as $key => $value) {
            // Convert comma-separated string to an array
            $parcelIdsArray = explode(',', $value->parcel_ids);
            $totalparcels   = count($parcelIdsArray); // Count the total parcels

            $datavalue[0] = $key + 1;
            $datavalue[1] = date('F d, Y', strtotime($value->date));
            $datavalue[2] = $value->created_at->format('g:i A');
            $datavalue[3] = $value->batchnumber;
            $datavalue[4] = $value->originhub->name;
            $datavalue[5] = $value->deliverymen->name;
            $datavalue[6] = $totalparcels; // Include the total parcels count
            $datavalue[7] = '<a class="btn btn-primary tthViwwButton" href="' . url('/agent/parcel/assigndel/report/' . $value->id) . '" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            array_push($data['data'], $datavalue);
        }

        return $data;
    }
    public function getrreturnreportdata(Request $request)
    {
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

        $query = TransferHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'return')
            ->with('originhub', 'destinationhub')
            ->orderBy('id', 'DESC');

        $count    = $query->count();
        $alldatas = $query->offset($start)->limit($length)->get();
        $data     = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($alldatas as $key => $value) {
            $datavalue[0] = $key + 1;
            $datavalue[1] = date('F d, Y', strtotime($value->date));
            $datavalue[2] = $value->created_at->format('g:i A');
            $datavalue[3] = $value->batchnumber;
            $datavalue[4] = $value->sealnumber;
            $datavalue[5] = $value->tagnumber;
            $datavalue[6] = $value->originhub->name;
            $datavalue[7] = $value->destinationhub->name;
            $datavalue[8] = '<a class="btn btn-primary tthViwwButton" href="' . url('/agent/parcel/transferhub/report/' . $value->id) . '" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            array_push($data['data'], $datavalue);
        }

        return $data;
    }
    public function getmerreturnreportdata(Request $request)
    {
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

        $query = RetMerchantHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'return')
            ->with('originhub', 'merchant')
            ->orderBy('id', 'DESC');

        $count    = $query->count();
        $alldatas = $query->offset($start)->limit($length)->get();
        $data     = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($alldatas as $key => $value) {

            //$merchantDetails = $value->getMerchantOrSenderDetails();
            $datavalue[0] = $key + 1;
            $datavalue[1] = date('F d, Y', strtotime($value->date));
            $datavalue[2] = $value->created_at->format('g:i A');
            $datavalue[3] = $value->batchnumber;
            $datavalue[4] = $value->originhub->name;
            $datavalue[5] = $value->merchant->companyName; //$merchantDetails->companyName;
            $datavalue[6] = '<a class="btn btn-primary tthViwwButton" href="' . url('/agent/parcel/returnmerchant/report/' . $value->id) . '" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>';

            array_push($data['data'], $datavalue);
        }

        return $data;
    }
    public function transferhubreportview(Request $request)
    {
        $data = TransferHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'transfer')
            ->with('originhub', 'destinationhub')
            ->get();
        $title = 'Transfer To Hub Waybill';

        //dd($data);

        return view('frontEnd.layouts.pages.agent.transferreportview', compact('data', 'title'));
    }

    public function transmerchantrptview(Request $request)
    {
        $data = RetMerchantHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'return')
            ->with('originhub', 'merchant')
            ->get();
        $title = 'Return To Merchant';

        return view('frontEnd.layouts.pages.agent.transmerchantrptview', compact('data', 'title'));
    }
    public function assigntodeltview(Request $request)
    {

        $title = 'Assign To Deliveryman';

        return view('frontEnd.layouts.pages.agent.assigntodeltview', compact('title'));
    }
    public function returnhubrptview(Request $request)
    {
        $data = TransferHistory::where('created_by', Session::get('agentId'))
            ->where('transfer_by', 'Agent')
            ->where('transfer_type', 'return')
            ->with('originhub', 'destinationhub')
            ->get();
        $title = 'Return To Origin Hub';

        return view('frontEnd.layouts.pages.agent.returnhubrptview', compact('data', 'title'));
    }

    public function transferhubreport(Request $request, $id)
    {
        $data    = TransferHistory::where('id', $id)->with('originhub', 'destinationhub')->first();
        $idArray = explode(',', $data->parcel_ids);
        $parcels = \App\Parcel::whereIn('id', $idArray)
            ->with('merchant', 'agent', 'parceltype')
            ->get();
        $totalParcel = $parcels->count();
        $totalCOD = $parcels->sum(function ($parcel) {
            if ($parcel->parcel_source === 'p2p' || $parcel->payment_option == 1) {
                return $parcel->package_value;
            } else {

                    return $parcel->cod;
                
            }
        }); 
        // $agentName = $parcels->first()?->agent?->name ?? 'unknown-agent';
        if ($data->transfer_type == 'transfer') {
            $title = 'Transfer To Hub Waybill';
            $printtitle = 'Transfer-to-hub-' . $data->done_by. '-' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A');

        } else {
            $title = 'Return To Origin Hub Waybill';
            $originhubName = $parcels->first()?->originhub?->name ?? 'unknown-originhub';
            $printtitle = 'Return-to-origin-hub-' . $originhubName . '-' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A');


        }

        return view('frontEnd.layouts.pages.agent.transferreport', compact('data', 'title', 'parcels', 'totalParcel' , 'totalCOD', 'printtitle'));
    }
    public function assigndelreport(Request $request, $id)
    {
        $data    = TransferHistory::where('id', $id)->with('originhub', 'deliverymen')->first();
        $idArray = explode(',', $data->parcel_ids);
        $parcels = \App\Parcel::whereIn('id', $idArray)
            ->with('merchant', 'agent', 'parceltype', 'p2pParcel', 'deliverytown')
            ->get();

        $agentInfo = Agent::find(Session::get('agentId'));

        $title = 'Assign To Deliveryman Parcels';
        return view('frontEnd.layouts.pages.agent.assigndeliverymanreport', compact('data', 'title', 'parcels', 'agentInfo'));
    }
    public function returnmerchant(Request $request, $id)
    {
        $data    = RetMerchantHistory::where('id', $id)->with('originhub', 'merchant')->first();
        $idArray = explode(',', $data->parcel_ids);
        $parcels = \App\Parcel::whereIn('id', $idArray)
            ->with('merchant', 'agent', 'parceltype')
            ->get();

        $title = 'Return To - ' . $data->merchant->companyName;
        $printtitle = 'Return To - ' . $data->merchant->companyName . '-' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A');

        return view('frontEnd.layouts.pages.agent.treturnmerchantreport', compact('data', 'title', 'parcels', 'printtitle'));
    }
    public function transfertohub(Request $request)
    {

        $request->validate([
            'agentId'   => 'required',
            'parcel_id' => 'required',
        ]);

        $parcels_id    = $request->parcel_id;
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;

        if (substr($parcels_id, 0, 1) == ',') {
            $parcels_id = substr($parcels_id, 1);
        }

        $parcels_id = explode(",", $parcels_id);
        $asigntype  = $request->asigntype;

        foreach ($parcels_id as $parcel_id) {
            $parcel              = Parcel::find($parcel_id);
            $parcel->agentId     = $request->agentId;
            $parcel->agentAmount = $parcel->cod;
            $parcel->status      = 2;
            $parcel->save();

            $note           = new Parcelnote();
            $note->parcelId = $parcel_id;
            $note->note     = "In Transit To Delivery Facility";
            $note->save();

            // history

            $history            = new History();
            $history->name      = $parcel->recipientName;
            $history->parcel_id = $parcel->id;
            $history->done_by   = $agentsettings->name;
            $history->status    = 'Transfer To Hub';
            $history->note      = 'In Transit To Delivery Facility';
            $history->date      = $parcel->updated_at;
            $history->save();
            // Email
            // Email
            if ($parcel->parcel_source == 'p2p') {
                $merchantDetails = $parcel->getMerchantOrSenderDetails();
                try {
                    if (! empty($merchantDetails)) {
                        Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                    }
                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            } else {

                try {
                    $validMerchant = Merchant::find($parcel->merchantId);
                    if (! empty($validMerchant)) {
                        Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                    }
                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            }

        }

        // transfer History
        $parcelIdsString                     = implode(',', $parcels_id);
        $transferHistory                     = new TransferHistory();
        $transferHistory->parcel_ids         = $parcelIdsString;
        $transferHistory->name               = 'Transfer Hub';
        $transferHistory->content            = 'Parcel';
        $transferHistory->transfer_type      = 'transfer';
        $transferHistory->done_by            = $agentsettings->name;
        $transferHistory->status             = 'Success';
        $transferHistory->note               = 'Transfer To Hub From ' . $agentsettings->name;
        $transferHistory->date               = now();
        $transferHistory->transfer_by        = 'Agent';
        $transferHistory->batchnumber        = 'BAT' . mt_rand(1111111111, 9999999999);
        $transferHistory->sealnumber         = 'SEL' . mt_rand(1111111111, 9999999999);
        $transferHistory->tagnumber          = 'TAG' . mt_rand(1111111111, 9999999999);
        $transferHistory->origin_hub_id      = $agentsettings->id;
        $transferHistory->destination_hub_id = $request->agentId;
        $transferHistory->created_by         = $agentsettings->id;
        $transferHistory->save();

        Toastr::success('message', 'Transfer to Hub successfully!');
        session()->flash('open_url', url('/agent/parcel/transferhub/report/' . $transferHistory->id));

        return redirect()->back();

    }
    public function bulkdeliverymanAssign(Request $request)
    {
        $request->validate([
            'deliverymanId' => 'required',
            'parcel_id'     => 'required',
            'asigntype'     => 'required',
        ]);

        $parcels_id = $request->parcel_id;

        if (substr($parcels_id, 0, 1) == ',') {
            $parcels_id = substr($parcels_id, 1);
        }

        $parcels_id = explode(",", $parcels_id);
        $asigntype  = $request->asigntype;

        if ($asigntype == 1) {

            foreach ($parcels_id as $parcel_id) {
                $parcel              = Parcel::find($parcel_id);
                $parcel->pickupmanId = $request->deliverymanId;
                $parcel->save();

                $note           = new Parcelnote();
                $note->parcelId = $parcel_id;
                $note->note     = "Pickup Man Asign";
                $note->save();
            }

        } else {

            foreach ($parcels_id as $parcel_id) {
                $parcel                = Parcel::find($parcel_id);
                $parcel->deliverymanId = $request->deliverymanId;
                $parcel->status        = 3;
                $parcel->save();

                $note           = new Parcelnote();
                $note->parcelId = $parcel_id;
                $note->note     = "Assigned To Delivery Man";
                $note->save();

                // history
                $id                 = Session::get('agentId');
                $agentInfo          = Agent::find($id);
                $history            = new History();
                $history->name      = $parcel->recipientName;
                $history->parcel_id = $parcel->id;
                $history->done_by   = $agentInfo->name;
                $history->status    = 'Assign To Deliveryman';
                $history->note      = 'Assign To Deliveryman';
                $history->date      = $parcel->updated_at;
                $history->save();

                // Generate Transfer History for each merchant

                $transferHistory                 = new TransferHistory();
                $transferHistory->parcel_ids     = $request->parcel_id;
                $transferHistory->name           = 'Assign To Deliveryman';
                $transferHistory->transfer_type  = 'assigndel';
                $transferHistory->done_by        = $agentInfo->name;
                $transferHistory->status         = 'Success';
                $transferHistory->note           = 'Return To Merchant From';
                $transferHistory->date           = now();
                $transferHistory->transfer_by    = 'Agent';
                $transferHistory->batchnumber    = 'BAT' . mt_rand(1111111111, 9999999999);
                $transferHistory->sealnumber     = 'SEL' . mt_rand(1111111111, 9999999999);
                $transferHistory->tagnumber      = 'TAG' . mt_rand(1111111111, 9999999999);
                $transferHistory->origin_hub_id  = $agentInfo->id;
                $transferHistory->created_by     = $agentInfo->id;
                $transferHistory->deliveryman_id = $request->deliverymanId;
                $transferHistory->save();
            }

        }

        Toastr::success('message', 'Riderman asign successfully!');

        return redirect()->back();

    }
    public function returntohub(Request $request)
    {

        $request->validate([
            'agentId'       => 'required',
            'ret_parcel_id' => 'required',
        ]);

        $parcels_id    = $request->ret_parcel_id;
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;

        if (substr($parcels_id, 0, 1) == ',') {
            $parcels_id = substr($parcels_id, 1);
        }

        $parcels_id = explode(",", $parcels_id);
        $asigntype  = $request->asigntype;

        foreach ($parcels_id as $parcel_id) {
            $parcel          = Parcel::find($parcel_id);
            $parcel->agentId = $request->agentId;
            $parcel->status  = 7;
            $parcel->save();

            $note           = new Parcelnote();
            $note->parcelId = $parcel_id;
            $note->note     = "Return To The Shipper";
            $note->save();

            // history
            $history            = new History();
            $history->name      = $parcel->recipientName;
            $history->parcel_id = $parcel->id;
            $history->done_by   = $agentsettings->name;
            $history->status    = 'Return To Origin Hub';
            $history->note      = 'Return To The Shipper';
            $history->date      = $parcel->updated_at;
            $history->save();
            // Email
            // Email
            if ($parcel->parcel_source == 'p2p') {
                $merchantDetails = $parcel->getMerchantOrSenderDetails();
                try {
                    if (! empty($merchantDetails)) {
                        Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                    }
                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            } else {
                try {
                    $validMerchant = Merchant::find($parcel->merchantId);
                    if (! empty($validMerchant)) {
                        Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                    }
                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            }

        }

        // transfer History
        $parcelIdsString                     = implode(',', $parcels_id);
        $transferHistory                     = new TransferHistory();
        $transferHistory->parcel_ids         = $parcelIdsString;
        $transferHistory->name               = 'Return To Origin Hub';
        $transferHistory->content            = 'Parcel';
        $transferHistory->transfer_type      = 'return';
        $transferHistory->done_by            = $agentsettings->name;
        $transferHistory->status             = 'Success';
        $transferHistory->note               = 'Return To Origin Hub From ' . $agentsettings->name;
        $transferHistory->date               = now();
        $transferHistory->transfer_by        = 'Agent';
        $transferHistory->batchnumber        = 'BAT' . mt_rand(1111111111, 9999999999);
        $transferHistory->sealnumber         = 'SEL' . mt_rand(1111111111, 9999999999);
        $transferHistory->tagnumber          = 'TAG' . mt_rand(1111111111, 9999999999);
        $transferHistory->origin_hub_id      = $agentsettings->id;
        $transferHistory->destination_hub_id = $request->agentId;
        $transferHistory->created_by         = $agentsettings->id;
        $transferHistory->save();

        Toastr::success('message', 'Return to Origin Hub successfully!');
        session()->flash('open_url', url('/agent/parcel/transferhub/report/' . $transferHistory->id));

        return redirect()->back();

    }

    public function parcelReceive(Request $request)
    {

        if (is_array($request->parcels)) {

            foreach ($request->parcels as $parcel) {
                $dbData = Parcel::where('id', $parcel)->with('agent', 'merchant')->first();

                $dbData->status = 10; // Arrived At Hub
                $dbData->save();

                $parcelNote           = new Parcelnote();
                $parcelNote->parcelId = $dbData->id;
                $parcelNote->note     = 'Arrived At Hub';
                $parcelNote->save();

                // history

                $history            = new History();
                $history->name      = $dbData->recipientName;
                $history->parcel_id = $dbData->id;
                $history->done_by   = $dbData->agent->name;
                $history->status    = 'Arrived At Hub';
                $history->note      = 'Parcel has arrived at Delivery Facility';
                $history->date      = $dbData->updated_at;
                $history->save();

                //send email to this
                if ($dbData->parcel_source == 'p2p') {
                    $merchantDetails = $dbData->getMerchantOrSenderDetails();
                    $agent           = Agent::find(Session::get('agentId'));
                    try {
                        if (! empty($merchantDetails)) {

                            Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $dbData, $history));
                        }
                        Log::info('Success: Parcel Received by agent and sent mail notification to Sender Success');
                    } catch (\Exception $exception) {
                        Log::info('Error: Parcel Received by agent and sent mail notification to Sender : ' . $exception->getMessage());
                    }
                } else {
                    try {
                        $merchant = Merchant::find($dbData->merchantId);
                        $agent    = Agent::find(Session::get('agentId'));

                        Mail::to($merchant->emailAddress)->send(new ParcelStatusUpdateEmail($merchant, $dbData, $history));

                        Log::info('Success: Parcel Received by agent and sent mail notification to Merchant Success');
                    } catch (\Exception $exception) {
                        Log::info('Error: Parcel Received by agent and sent mail notification to Merchant : ' . $exception->getMessage());
                    }
                }

                // Send Sms

                // sendTermiiMessage('2348032968501', 'Hello from Termii!');
                $smsText       = "Hi {$dbData->recipientName}, your ZIDROP parcel ({$dbData->trackingCode}) from {$dbData->merchant->companyName} has arrived {$dbData->agent->name}. Questions? call {$dbData->agent->phone}.";
                $smssendNumber = $dbData->recipientPhone ?? '48032968501';
                sendTermiiMessage($smssendNumber, $smsText);

            }

            return response()->json(['success' => 'success'], 200); // Status code here
        }

        return response()->json(['error' => 'invalid'], 401); // Status code here

    }
    // public function PrintSelectedItems(Request $request)
    // {

    //     $parcels = \App\Parcel::where('agentId', Session::get('agentId'))
    //     ->whereIn('id', $request->parcels)
    //     ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen','agent', 'parceltype', 'p2pParcel'])
    //     ->get();

    //     $pdf = new \Mpdf\Mpdf([
    //         'mode' => 'utf-8',
    //         // 'format' => 'A4',
    //         'format' => [210, 148], // Width = 210mm (A4 width), Height = 148mm (Half A4)
    //         'orientation' => 'P',
    //         'margin_top' => '5',
    //         'margin_bottom' => '5',
    //         'margin_left' => '5',
    //         'margin_right' => '5',
    //         'default_font_size' => '10',
    //         'default_font' => 'dejavusans', // Supports bold
    //         'autoScriptToLang' => true,
    //         'autoLangToFont' => true,
    //         'margin_footer' => '0',
    //         'shrink_tables' => 1, // ✅ Fixes blank page issue for large tables

    //     ]);
    //     $pdf->WriteHTML('');

    //     $view = 'pdf.pdf';
    //     //$data = compact('show_data');
    //     $data = compact('parcels');

    //     $html = trim(view($view, $data));
    //     // $html = trim(view('pdf.pdf', compact('parcels'))->render());
    //     $pdf->WriteHTML($html);

    //     // Generate a filename with the current date and time
    //     $filename = 'zidrop_Parcels_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g.i.A') . '.pdf';

    //     return response()->streamDownload(function () use ($pdf) {
    //         echo $pdf->Output('', 'S'); // Output as string
    //     }, $filename, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment',
    //     ]);

    // }
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

    public function statusupdate(Request $request)
    {
        //   return $request->all();
        $this->validate($request, [
            'status' => 'required',
        ]);
        $parcel             = Parcel::find($request->hidden_id);
        $parcel->status     = $request->status;
        $parcel->updated_at = Carbon::now();
        $parcel->save();

        $pnote          = Parceltype::find($request->status);
        $note           = new Parcelnote();
        $note->parcelId = $request->hidden_id;
        $note->note     = $request->note;
        // $note->note = "Your parcel ".$pnote->title;
        $note->save();

        $deliverymanInfo = Deliveryman::where(['id' => $parcel->deliverymanId])->first();
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $parcel->merchantId)->where('is_active', 1)->first();
        $Agent = Agent::find($parcel->agentId);

        if ($request->status == 2 && $deliverymanInfo != null) {
            $merchantinfo = Agent::find($parcel->merchantId);
            $data         = [
                'contact_mail' => $merchantinfo->email,
                'ridername'    => $deliverymanInfo->name,
                'riderphone'   => $deliverymanInfo->phone,
                'codprice'     => $parcel->cod,
                'trackingCode' => $parcel->trackingCode,
            ];
            $send = Mail::send('frontEnd.emails.percelassign', $data, function ($textmsg) use ($data) {
                $textmsg->from('info@aschi.com.bd');
                $textmsg->to($data['contact_mail']);
                $textmsg->subject('Percel Assign Notification');
            });
        }

        if ($request->status == 3) {
            $codcharge              = 0;
            $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
            $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
            // $parcel->codCharge = $codcharge;
            $parcel->save();
        } elseif ($request->status == 4) {
            $merchantinfo    = Merchant::find($parcel->merchantId);
            $merchantDetails = $parcel->getMerchantOrSenderDetails();

            $data = [
                'contact_mail' => $merchantDetails->emailAddress,
                'trackingCode' => $parcel->trackingCode,
            ];

            // Agent Commission calculation
           
            if ($Agent) {
                if ($Agent->commisiontype == 1) {
                    // 1 for flat rate
                    $parcel->agent_commission = $Agent->commision;
                    $parcel->save();
                    $Agent->total_commission += $Agent->commision;
                    $Agent->save();

                } elseif ($Agent->commisiontype == 2) {
                    // 2 for perchantage
                    $parcel->agent_commission = ($parcel->deliveryCharge * $Agent->commision) / 100;
                    $parcel->save();
                    $Agent->total_commission += ($parcel->deliveryCharge * $Agent->commision) / 100;
                    $Agent->save();
                }
            }
            // Deliveryman Commission calculation
            $Deliveryman = Deliveryman::find($parcel->deliverymanId);
            if ($Deliveryman) {
                if ($Deliveryman->commisiontype == 1) {
                    // 1 for flat rate
                    $parcel->deliveryman_commission = $Deliveryman->commision;
                    $parcel->save();
                    $Deliveryman->total_commission += $Deliveryman->commision;
                    $Deliveryman->save();

                } elseif ($Deliveryman->commisiontype == 2) {
                    // 2 for perchantage
                    $parcel->deliveryman_commission = ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                    $parcel->save();
                    $Deliveryman->total_commission += ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                    $Deliveryman->save();
                }
            }

            //  $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){

            //  $textmsg->from('info@aschi.com.bd');

            //  $textmsg->to($data['contact_mail']);

            //  $textmsg->subject('Percel Assign Notification');
            // });

        } elseif ($request->status == 6) {
          
            // return $request->all();
            // 6 for partial Deliver
            // Agent Commission calculation
            $Agent = Agent::find($parcel->agentId);
            if ($Agent) {
                if ($Agent->commisiontype == 1) {
                    // 1 for flat rate
                    $parcel->agent_commission = $Agent->commision;
                    $parcel->save();
                    $Agent->total_commission += $Agent->commision;
                    $Agent->save();

                } elseif ($Agent->commisiontype == 2) {
                    // 2 for perchantage
                    $parcel->agent_commission = ($parcel->deliveryCharge * $Agent->commision) / 100;
                    $parcel->save();
                    $Agent->total_commission += ($parcel->deliveryCharge * $Agent->commision) / 100;
                    $Agent->save();
                }
            }
            // Deliveryman Commission calculation
            $Deliveryman = Deliveryman::find($parcel->deliverymanId);
            if ($Deliveryman) {
                if ($Deliveryman->commisiontype == 1) {
                    // 1 for flat rate
                    $parcel->deliveryman_commission = $Deliveryman->commision;
                    $parcel->save();
                    $Deliveryman->total_commission += $Deliveryman->commision;
                    $Deliveryman->save();

                } elseif ($Deliveryman->commisiontype == 2) {
                    // 2 for perchantage
                    $parcel->deliveryman_commission = ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                    $parcel->save();
                    $Deliveryman->total_commission += ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                    $Deliveryman->save();
                }
            }

            if ($parcel->payment_option == 2 && $parcel->parcel_source !== 'p2p') {
                $merchantinfo    = Merchant::find($parcel->merchantId);
                // 2 for pay on delivery
                // return $request->all();
                $charge            = \App\ChargeTarif::where('pickup_cities_id', $parcel->pickup_cities_id)->where('delivery_cities_id', $parcel->delivery_cities_id)->first();
                $town              = \App\Town::where('id', $parcel->delivery_town_id)->where('cities_id', $parcel->delivery_cities_id)->first();
                $partialPaymentAmt = $request->partial_payment ? remove_commas($request->partial_payment) : 0;
                // Cod Cal
                if ($merchantinfo->cod_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->cod_permission == 0)) {
                    $codcharge = 0; // Override if permission is disabled
                }else{
                    $codcharge         = ($partialPaymentAmt * $charge->codcharge) / 100;
                }

                // Tax Calculation And Insurance calculation
                $tax = $parcel->deliveryCharge * $charge->tax / 100;
                $tax = round($tax, 2);
                // Insurance
                
                if ($merchantinfo->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                    $insurance = 0; // Override if permission is disabled
                }else{
                    $insurance = $partialPaymentAmt * $charge->insurance / 100;
                    $insurance = round($insurance, 2);
                }

                $amount = $partialPaymentAmt - ($codcharge + $parcel->deliveryCharge + $tax + $insurance);

                $parcel->cod            = $partialPaymentAmt;
                $parcel->agentAmount    = $partialPaymentAmt;
                $parcel->merchantAmount = $amount;
                $parcel->merchantDue    = $amount;
                $parcel->codCharge      = $codcharge;
                $parcel->tax            = $tax;
                $parcel->insurance      = $insurance;
                $parcel->save();

            } else {
                Toastr::error('message', 'The Parcel is not Partial Delivery Facility!');
            }

        } elseif ($request->status == 8) {
            $parcel                 = Parcel::find($request->hidden_id);
            $returncharge           = $parcel->deliveryCharge / 2;
            $parcel->merchantAmount = $parcel->merchantAmount - $returncharge;
            $parcel->merchantDue    = $parcel->merchantAmount - $returncharge;
            $parcel->deliveryCharge = $parcel->deliveryCharge + $returncharge;
            $parcel->save();
        } elseif ($request->status == 10) {
            // 10 for Arrive at hub
            $dbData = Parcel::where('id', $request->hidden_id)->with('agent', 'merchant')->first();
            // sendTermiiMessage('2348032968501', 'Hello from Termii!');
            $smsText       = "Hi {$dbData->recipientName}, your ZIDROP parcel ({$dbData->trackingCode}) from {$dbData->merchant->companyName} has arrived {$dbData->agent->name}. Questions? call {$dbData->agent->phone}.";
            $smssendNumber = $dbData->recipientPhone;
            sendTermiiMessage($smssendNumber, $smsText);

        }

        $pstatus = Parceltype::find($request->status);

        $pstatus = $pstatus->title;

        //Save to History table


        $history            = new History();
        $history->name      = $parcel->recipientName;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = $Agent->name;
        $history->status    = $pstatus;
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

        // Email
        if ($parcel->parcel_source == 'p2p') {
            $merchantDetails = $parcel->getMerchantOrSenderDetails();
            try {
                if (! empty($merchantDetails)) {
                    Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                }
            } catch (\Exception $exception) {
                Log::info('Parcel status update mail error: ' . $exception->getMessage());
            }
        } else {

            try {
                $validMerchant = Merchant::find($parcel->merchantId);

                if (! empty($validMerchant)) {Mail::to([
                    $validMerchant->emailAddress,
                ])->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));}

            } catch (\Exception $exception) {
                Log::info('Agent Parcel status update mail error: ' . $exception->getMessage());
            }

        }

        Toastr::success('message', 'Parcel information update successfully!');

        return redirect()->back();
    }

    public function massstatusupdate(Request $request)
    {
        //   return $request->all();
        $this->validate($request, [
            'status' => 'required',
            'note'   => 'nullable',
        ]);
        $parcels_ids = $request->par_up_mass_hidden_ids;

        if (substr($parcels_ids, 0, 2) == 'on') {
            $parcels_ids = substr($parcels_ids, 2);
        }

        if (substr($parcels_ids, 0, 1) == ',') {
            $parcels_ids = substr($parcels_ids, 1);
        }

        $parcels_ids = explode(',', $parcels_ids);
        foreach ($parcels_ids as $id) {
            $parcel             = Parcel::find($id);
            $parcel->status     = $request->status;
            $parcel->updated_at = Carbon::now();
            $parcel->save();

            $pnote          = Parceltype::find($request->status);
            $note           = new Parcelnote();
            $note->parcelId = $id;
            $note->note     = $request->note;
            // $note->note = "Your parcel ".$pnote->title;
            $note->save();

            $deliverymanInfo = Deliveryman::where(['id' => $parcel->deliverymanId])->first();
            $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $parcel->merchantId)->where('is_active', 1)->first();
            $Agent = Agent::find($parcel->agentId);

            if ($request->status == 2 && $deliverymanInfo != null) {
                $merchantinfo = Agent::find($parcel->merchantId);
                $data         = [
                    'contact_mail' => $merchantinfo->email,
                    'ridername'    => $deliverymanInfo->name,
                    'riderphone'   => $deliverymanInfo->phone,
                    'codprice'     => $parcel->cod,
                    'trackingCode' => $parcel->trackingCode,
                ];
                $send = Mail::send('frontEnd.emails.percelassign', $data, function ($textmsg) use ($data) {
                    $textmsg->from('info@aschi.com.bd');
                    $textmsg->to($data['contact_mail']);
                    $textmsg->subject('Percel Assign Notification');
                });
            }

            if ($request->status == 3) {
                $codcharge              = 0;
                $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
                $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
                // $parcel->codCharge = $codcharge;
                $parcel->save();
            } elseif ($request->status == 4) {
                $merchantinfo = Merchant::find($parcel->merchantId);
                $data         = [
                    'contact_mail' => $merchantinfo->emailAddress,
                    'trackingCode' => $parcel->trackingCode,
                ];
                // Agent Commission calculation
                if ($Agent) {
                    if ($Agent->commisiontype == 1) {
                        // 1 for flat rate
                        $parcel->agent_commission = $Agent->commision;
                        $parcel->save();
                        $Agent->total_commission += $Agent->commision;
                        $Agent->save();

                    } elseif ($Agent->commisiontype == 2) {
                        // 2 for perchantage
                        $parcel->agent_commission = ($parcel->deliveryCharge * $Agent->commision) / 100;
                        $parcel->save();
                        $Agent->total_commission += ($parcel->deliveryCharge * $Agent->commision) / 100;
                        $Agent->save();
                    }
                }
                // Deliveryman Commission calculation
                $Deliveryman = Deliveryman::find($parcel->deliverymanId);
                if ($Deliveryman) {
                    if ($Deliveryman->commisiontype == 1) {
                        // 1 for flat rate
                        $parcel->deliveryman_commission = $Deliveryman->commision;
                        $parcel->save();
                        $Deliveryman->total_commission += $Deliveryman->commision;
                        $Deliveryman->save();

                    } elseif ($Deliveryman->commisiontype == 2) {
                        // 2 for perchantage
                        $parcel->deliveryman_commission = ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                        $parcel->save();
                        $Deliveryman->total_commission += ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                        $Deliveryman->save();
                    }
                }

                //  $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){

                //  $textmsg->from('info@aschi.com.bd');

                //  $textmsg->to($data['contact_mail']);

                //  $textmsg->subject('Percel Assign Notification');
                // });

            } elseif ($request->status == 6) {
                // Agent Commission calculation
                
                if ($Agent) {
                    if ($Agent->commisiontype == 1) {
                        // 1 for flat rate
                        $parcel->agent_commission = $Agent->commision;
                        $parcel->save();
                        $Agent->total_commission += $Agent->commision;
                        $Agent->save();

                    } elseif ($Agent->commisiontype == 2) {
                        // 2 for perchantage
                        $parcel->agent_commission = ($parcel->deliveryCharge * $Agent->commision) / 100;
                        $parcel->save();
                        $Agent->total_commission += ($parcel->deliveryCharge * $Agent->commision) / 100;
                        $Agent->save();
                    }
                }
                // Deliveryman Commission calculation
                $Deliveryman = Deliveryman::find($parcel->deliverymanId);
                if ($Deliveryman) {
                    if ($Deliveryman->commisiontype == 1) {
                        // 1 for flat rate
                        $parcel->deliveryman_commission = $Deliveryman->commision;
                        $parcel->save();
                        $Deliveryman->total_commission += $Deliveryman->commision;
                        $Deliveryman->save();

                    } elseif ($Deliveryman->commisiontype == 2) {
                        // 2 for perchantage
                        $parcel->deliveryman_commission = ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                        $parcel->save();
                        $Deliveryman->total_commission += ($parcel->deliveryCharge * $Deliveryman->commision) / 100;
                        $Deliveryman->save();
                    }
                }
                if ($parcel->payment_option == 2 && $parcel->parcel_source !== 'p2p') {
                    $merchantinfo    = Merchant::find($parcel->merchantId);
                // 2 for pay on delivery
                    $charge            = \App\ChargeTarif::where('pickup_cities_id', $parcel->pickup_cities_id)->where('delivery_cities_id', $parcel->delivery_cities_id)->first();
                    $town              = \App\Town::where('id', $parcel->delivery_town_id)->where('cities_id', $parcel->delivery_cities_id)->first();
                    $partialPaymentAmt = $request->partial_payment ? remove_commas($request->partial_payment) : 0;
                    // Cod Cal
                    if ($merchantinfo->cod_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->cod_permission == 0)) {
                        $codcharge = 0; // Override if permission is disabled
                    }else{
                        $codcharge         = ($partialPaymentAmt * $charge->codcharge) / 100;
                    }

                    // Tax Calculation And Insurance calculation
                    $tax       = $parcel->deliverycharge * $charge->tax / 100;
                    $tax       = round($tax, 2);
                    if ($merchantinfo->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                        $insurance = 0; // Override if permission is disabled
                    }else{
                        $insurance = $partialPaymentAmt * $charge->insurance / 100;
                        $insurance = round($insurance, 2);
                    }

                    $amount = $partialPaymentAmt - ($codcharge + $parcel->deliveryCharge + $tax + $insurance);

                    $parcel->cod            = $partialPaymentAmt;
                    $parcel->agentAmount    = $partialPaymentAmt;
                    $parcel->merchantAmount = $amount;
                    $parcel->merchantDue    = $amount;
                    $parcel->codCharge      = $codcharge;
                    $parcel->tax            = $tax;
                    $parcel->insurance      = $insurance;
                    $parcel->save();

                } else {
                    Toastr::error('message', 'The Parcel is not Partial Delivery Facility!');
                }

            } elseif ($request->status == 8) {
                $parcel                 = Parcel::find($id);
                $returncharge           = $parcel->deliveryCharge / 2;
                $parcel->merchantAmount = $parcel->merchantAmount - $returncharge;
                $parcel->merchantDue    = $parcel->merchantAmount - $returncharge;
                $parcel->deliveryCharge = $parcel->deliveryCharge + $returncharge;
                $parcel->save();
            } elseif ($request->status == 10) {
                // 10 for Arrive at hub
                $dbData = Parcel::where('id', $id)->with('agent', 'merchant')->first();
                // sendTermiiMessage('2348032968501', 'Hello from Termii!');
                $smsText       = "Hi {$dbData->recipientName}, your ZIDROP parcel ({$dbData->trackingCode}) from {$dbData->merchant->companyName} has arrived {$dbData->agent->name}. Questions? call {$dbData->agent->phone}.";
                $smssendNumber = $dbData->recipientPhone;
                sendTermiiMessage($smssendNumber, $smsText);

            }

            $pstatus = Parceltype::find($request->status);

            $pstatus = $pstatus->title;

            //Save to History table

            $history            = new History();
            $history->name      = $parcel->recipientName;
            $history->parcel_id = $id;
            $history->done_by   = $Agent->name;
            $history->status    = $pstatus;
            $history->note      = $request->note;
            $history->date      = $parcel->updated_at;
            $history->save();

            // Email
            if ($parcel->parcel_source == 'p2p') {
                $merchantDetails = $parcel->getMerchantOrSenderDetails();
                try {
                    if (! empty($merchantDetails)) {
                        Mail::to($merchantDetails->emailAddress)->send(new ParcelStatusUpdateEmail($merchantDetails, $parcel, $history));
                    }
                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            } else {

                try {
                    $validMerchant = Merchant::find($parcel->merchantId);

                    if (! empty($validMerchant)) {
                        \Illuminate\Support\Facades\Mail::to([
                            $validMerchant->emailAddress,
                        ])->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                    }

                } catch (\Exception $exception) {
                    Log::info('Agent Parcel status update mail error: ' . $exception->getMessage());
                }
            }

        }

        Toastr::success('message', 'Parcel information update successfully!');

        return redirect()->back();
    }

    public function logout()
    {
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');

        return redirect('agent/logout');
    }

    public function pickup()
    {
        $show_data = DB::table('pickups')
            ->where('pickups.agent', Session::get('agentId'))
            ->orderBy('pickups.id', 'DESC')
            ->select('pickups.*')
            ->get();
        $deliverymen = Deliveryman::where('status', 1)->get();

        return view('frontEnd.layouts.pages.agent.pickup', compact('show_data', 'deliverymen'));
    }

    public function pickupdeliverman(Request $request)
    {
        $this->validate($request, [
            'deliveryman' => 'required',
        ]);
        $pickup              = Pickup::find($request->hidden_id);
        $pickup->deliveryman = $request->deliveryman;
        $pickup->save();

        Toastr::success('message', 'A deliveryman asign successfully!');

        return redirect()->back();
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
        $agentInfo       = Agent::find($parcel->merchantId);
        $data            = [
            'contact_mail' => $agentInfo->email,
            'ridername'    => $deliverymanInfo->name,
            'riderphone'   => $deliverymanInfo->phone,
            'codprice'     => $pickup->cod,
        ];
        $send = Mail::send('frontEnd.emails.percelassign', $data, function ($textmsg) use ($data) {
            $textmsg->from('info@aschi.com.bd');
            $textmsg->to($data['contact_mail']);
            $textmsg->subject('Pickup Assign Notification');
        });

    }

    public function pickupstatus(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $pickup         = Pickup::find($request->hidden_id);
        $pickup->status = $request->status;
        $pickup->save();

        if ($request->status == 2) {
            $deliverymanInfo = Deliveryman::where(['id' => $pickup->deliveryman])->first();

            // $data = array(

            //  'name' => $deliverymanInfo->name,

            //  'companyname' => $merchantInfo->companyName,

            //  'phone' => $deliverymanInfo->phone,

            //  'address' => $merchantInfo->pickLocation,

            // );

            // $send = Mail::send('frontEnd.emails.pickupdeliveryman', $data, function($textmsg) use ($data){

            //  $textmsg->from('info@aschi.com.bd');

            //  $textmsg->to($data['contact_mail']);

            //  $textmsg->subject('Pickup request update');
            // });
        }

        Toastr::success('message', 'Pickup status update successfully!');

        return redirect()->back();
    }

    public function passreset()
    {
        return view('frontEnd.layouts.pages.agent.passreset');
    }

    public function passfromreset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);
        $validAgent = Agent::Where('email', $request->email)
            ->first();

        if ($validAgent) {
            $verifyToken               = rand(111111, 999999);
            $validAgent->passwordReset = $verifyToken;
            $validAgent->save();
            Session::put('resetAgentId', $validAgent->id);

            $data = [
                'contact_mail' => $validAgent->email,
                'verifyToken'  => $verifyToken,
            ];
            $send = Mail::send('frontEnd.layouts.pages.agent.forgetemail', $data, function ($textmsg) use ($data) {
                $textmsg->from('support@zuri.express');
                $textmsg->to($data['contact_mail']);
                $textmsg->subject('Forget password token');
            });

            return redirect('agent/resetpassword/verify');
        } else {
            Toastr::error('Sorry! You have no account', 'warning!');

            return redirect()->back();
        }

    }

    public function saveResetPassword(Request $request)
    {
        $validAgent = Agent::find(Session::get('resetAgentId'));

        if ($validAgent->passwordReset == $request->verifyPin) {
            $validAgent->password      = bcrypt(request('newPassword'));
            $validAgent->passwordReset = null;
            $validAgent->save();

            Session::forget('resetAgentId');
            Session::put('agentId', $validAgent->id);
            Toastr::success('Wow! Your password reset successfully', 'success!');

            return redirect('agent/dashboard');
        } else {
            Toastr::error('Sorry! Your process something wrong', 'warning!');

            return redirect()->back();
        }

    }

    public function resetpasswordverify()
    {

        if (Session::get('resetAgentId')) {
            return view('frontEnd.layouts.pages.agent.passwordresetverify');
        } else {
            Toastr::error('Sorry! Your process something wrong', 'warning!');

            return redirect('forget/password');
        }

    }

    public function export(Request $request)
    {
        return Excel::download(new AgentParcelExport(), 'parcel.xlsx');

    }

    public function get_parcel_by_qr(Request $request, $trackingCode)
    {

        $OLDTrackingCodesQRscans = session()->get('OLDTrackingCodesQRscans_agents');
        $sessionBeepSound        = session()->get('beepSound');

        $beepSound     = $sessionBeepSound ?? false;
        $beepSoundPass = $beepSound;

        if (! empty($OLDTrackingCodesQRscans)) {
            if (! in_array($trackingCode, $OLDTrackingCodesQRscans)) {
                $OLDTrackingCodesQRscans[] = $trackingCode;
                session()->put('OLDTrackingCodesQRscans_agents', $OLDTrackingCodesQRscans);
                $beepSound = true;
            }

        } else {
            $OLDTrackingCodesQRscans[] = $trackingCode;
            session()->put('OLDTrackingCodesQRscans_agents', $OLDTrackingCodesQRscans);
            $beepSound = true;
        }

        // return $OLDTrackingCodesQRscans;

        $slug = $request->input('slug');
        if ($slug === 'all') {
            $parceltype = '';
        } else {
            $parceltype = Parceltype::where('slug', $slug)->first();
        }
        // Datatable
        $start = 0;

        if (isset($request->start)) {
            $start = $request->start;
        }

        $draw = 1;

        if (isset($request->draw)) {
            $draw = $request->draw;
        }

        $length = 50;

        if (isset($request->length)) {
            $length = $request->length;
        }

        $show_data = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->whereIn('trackingCode', $OLDTrackingCodesQRscans)
            ->orderBy('updated_at', 'DESC')
            ->get();

        // Data table
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => count($show_data),
            'recordsFiltered' => count($show_data),
            'data'            => [],
        ];

        $html  = '';
        $total = 0;

        foreach ($show_data as $key => $value) {
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $merchantInfo    = Merchant::find($value->merchantId);
            $parcelstatus    = Parceltype::find($value->status);
            $merchantDetails = $value->getMerchantOrSenderDetails();

            if ($parceltype && $parceltype->title !== $parcelstatus->title) {
                continue;
            }
            $total += 1;
            $html .= '<tr class="data_all_trs">';
            $html .= '<td><input type="checkbox" class="selectItemCheckbox" checked value="' . $value->id . '" data-status="' . $parcelstatus->id . '" data-parcel_status_update_sl="' . $parcelstatus->sl . '" checked name="parcel_id[]" form="myform"></form></td>';
            $html .= '<td>' . $value->trackingCode . '</td>';
            // if($value->parcel_source == 'p2p'){
            //     $html .= '<td><ul><li class="m-1"><button class="btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $value->p2pParcel->sender_name .  '" data-type="'. 'p2p' . '" data-phonenumber = "' .  $value->p2pParcel->sender_mobile . '" data-emailaddress = "' . $value->p2pParcel->sender_email . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) .  '"data-package_value = "' . number_format($value->package_value, 2) .'" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '" ><i class="fa fa-eye"></i></button></li>';
            // }else{
            $html .= '<td><ul><li class="m-1"><button class="btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '"data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '" ><i class="fa fa-eye"></i></button></li>';
            // }

            if ($value->status != 8) {
                $html .= '<li class="m-1"><button class="btn-danger" href="#" id="sUpdateModal" title="Action" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"data-parcel_status_update_sl="' . $parcelstatus->sl . '"><i class="fa fa-sync-alt"></i></button></li>';
            }

            $html .= '<li class="m-1"><a class="btn btn-primary p-2" href="' . url('agent/parcel/invoice/' . $value->id) . '" target="_blank"  title="Invoice"><i class="fas fa-list"></i></a></li>';

            $html .= '</ul></td>';
            $html .= '<td>' . $value->created_at . '</td>';
            if ($value->parcel_source == 'p2p') {
                $html .= '<td>' . 'P2P' . '</td>';
            } else {
                $html .= '<td>' . $merchantDetails->companyName . '</td>';
            }
            $html .= '<td>' . $value->recipientName . '</td>';
            $html .= '<td>' . $value->recipientPhone . '</td>';

            if ($value->deliverymanId) {
                $html .= '<td>' . $deliverymanInfo->name . '</td>';
            } else {
                $html .= '<td><button class="btn btn-primary" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $value->phoneNumber . '">Asign</button></td>';
            }
            $html .= '<td>' . $parcelstatus->title . '</td>';
            $html .= '<td>' . number_format($value->cod, 2) . '</td>';
            $html .= '<td>' . number_format($value->deliveryCharge, 2) . '</td>';
            $html .= '<td>' . number_format($value->tax, 2) . '</td>';
            $html .= '<td>' . number_format($value->insurance, 2) . '</td>';
            $html .= '<td>' . number_format(($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance)), 2) . '</td>';
            $html .= '<td>' . date('F d, Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at)) . '</td>';

            if ($value->merchantpayStatus == null) {
                $html .= '<td>NULL</td>';
            } elseif ($value->merchantpayStatus == 0) {
                $html .= '<td>Processing</td>';
            } else {
                $html .= '<td>Paid</td>';
            }

            $html .= '<td>' . (isset($value->merchant) ? $value->merchant->id : 'P2P') . '</td>';
            $parcelnote = Parcelnote::where('parcelId', $value->id)
                ->orderBy('id', 'DESC')
                ->first();

            if (! empty($parcelnote)) {
                $html .= '<td>' . $parcelnote->note . '</td>';
            } else {
                $html .= '<td></td>';
            }

            $html .= '</tr>';

        }

        $beepSoundPass = $beepSound;
        session()->put('beepSound', false);

        if (count($show_data) > 0) {
            return response()->json([
                'status'    => true,
                'success'   => true,
                'html'      => $html,
                'beepSound' => $beepSoundPass,
                'total'     => $total,
            ]);

        } else {
            return response()->json([
                'status'  => false,
                'success' => false,
                'html'    => null,
                'message' => 'No Data Found',
            ]);
        }

    }

    public function create()
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        if ($agentsettings->agent_create_parcel == 0) {
            Toastr::error('Sorry! Your account is not permit to create parcel', 'warning!');

            return redirect()->back();
        }
        $merchants = Merchant::with('activeSubscription')->orderBy('id', 'DESC')->get();
        // $packages = Deliverycharge::where('status', 1)->get();

        return view('frontEnd.layouts.pages.agent.addparcel.create', compact('merchants'));
    }
    public function p2pcreate()
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        $results       = DB::table('paymentapis')->where('id', 1)->first();
        if ($agentsettings->p2p_permission == 0) {
            Toastr::error('Sorry! Your account is not permit to create P2P parcel', 'warning!');

            return redirect()->back();
        }

        return view('frontEnd.layouts.pages.agent.addparcel.p2pcreate', compact('results', 'agentsettings'));
    }

    public function parcelstore(Request $request)
    {

        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        if ($agentsettings->agent_create_parcel == 0) {
            Toastr::error('Sorry! Your account is not permit to create parcel', 'warning!');
            return redirect()->back();
        }
        // return $request->all();
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

        $charge     = \App\ChargeTarif::where('pickup_cities_id', $request->pickupcity)->where('delivery_cities_id', $request->deliverycity)->first();
        $town       = \App\Town::where('id', $request->deliverytown)->where('cities_id', $request->deliverycity)->first();
        $merchant   = Merchant::find($request->merchantId);
        $codAmt     = $request->cod ? remove_commas($request->cod) : 0;
        $packageAmt = $request->package_value ? remove_commas($request->package_value) : 0;
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $request->merchantId)->where('is_active', 1)->first();

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
            // 2 for pay on delivery
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

            $merchantAmount = $codAmt - ($deliverycharge + $codcharge + $tax + $insurance);
            $merchantDue    = $codAmt - ($deliverycharge + $codcharge + $tax + $insurance);

        } else {
            $cod = $packageAmt;

            $insurance = $cod ? $cod * $charge->insurance / 100 : 0;
            if ($merchant->ins_cal_permission == 0 || ($activeSubPlan && $activeSubPlan->plan->insurance_permission == 0)) {
                $insurance = 0; // Override if permission is disabled
            }
            $insurance = round($insurance, 2);

            $merchant       = Merchant::find($request->merchantId);
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
        $store_parcel->merchantId         = $request->merchantId;
        $store_parcel->payment_option     = $request->payment_option;
        $store_parcel->order_number       = $request->order_number;
        $store_parcel->percelType         = $request->percelType;
        $store_parcel->cod                = $codAmt;
        $store_parcel->package_value      = $packageAmt;
        $store_parcel->tax                = $tax;
        $store_parcel->insurance          = $merchant->ins_cal_permission == 0 ? 0 : $insurance;
        $store_parcel->recipientName      = $request->name;
        $store_parcel->recipientAddress   = $request->address;
        $store_parcel->recipientPhone     = $request->phonenumber;
        $store_parcel->productWeight      = $weight;
        $store_parcel->productName        = $request->productName;
        $store_parcel->productQty         = $request->productQty;
        $store_parcel->productColor       = $request->productColor;
        $store_parcel->trackingCode       = 'ZD' . mt_rand(1111111111, 9999999999);
        $store_parcel->note               = $request->note;
        $store_parcel->deliveryCharge     = $deliverycharge;
        $store_parcel->codCharge          = $merchant->cod_cal_permission == 0 ? 0 : $codcharge;
        $store_parcel->reciveZone         = $request->reciveZone;
        $store_parcel->merchantAmount     = $merchantAmount;
        $store_parcel->merchantDue        = $merchantDue;
        $store_parcel->orderType          = $request->package ?? 0;
        $store_parcel->codType            = 1;
        $store_parcel->status             = 1;
        $store_parcel->pickup_cities_id   = $request->pickupcity;
        $store_parcel->delivery_cities_id = $request->deliverycity;
        $store_parcel->pickup_town_id     = $request->pickuptown;
        $store_parcel->delivery_town_id   = $request->deliverytown;
        $store_parcel->agentId            = session('agentId');
        // $store_parcel->vehicle_type = $request->vehicle_type;
        // $store_parcel->pickup_or_drop_option = $request->pickupOrdropOff;
        // $store_parcel->pickup_or_drop_location = $request->addressofSender;

        // agent commision

        // if ($agentsettings->commisiontype == 1) {
        //     $commision = $agentsettings->commision;
        // } else {
        //     $commision = ($agentsettings->commision * $deliverycharge) / 100;
        // }

        // $commision += $codAmt;

        $store_parcel->agentAmount = $codAmt;

        $store_parcel->save();

        if ($store_parcel->merchantId != '' && $store_parcel->merchantId != null) { // Ensure the condition is meaningful
            if ($request->payment_option == 1) {
                RemainTopup::create([
                    'parcel_id'     => $store_parcel->id,
                    'parcel_status' => 1,
                    'merchant_id'   => $store_parcel->merchantId,
                    'amount'        => $deliverycharge + $tax + $insurance,
                ]);
            }
        }

        $history            = new History();
        $history->name      = "Customer: " . $store_parcel->recipientName . "<br><b>(Created By : )</b>" . $agentsettings->name;
        $history->parcel_id = $store_parcel->id;
        $history->done_by   = $agentsettings->name;
        $history->status    = 'Parcel Created By ' . $agentsettings->name;
        $history->note      = $request->note ? $request->note : 'Pending Pickup';
        $history->date      = $store_parcel->updated_at;
        $history->save();

        $note           = new Parcelnote();
        $note->parcelId = $store_parcel->id;
        $note->note     = 'Pending Pickup';
        $note->save();

        Toastr::success('Success!', 'Thanks! your parcel add successfully');
        session()->flash('open_url', url('/agent/parcel/invoice/' . $store_parcel->id));

        return redirect()->back();
    }

    public function parceledit($id)
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        if ($agentsettings->agent_create_parcel == 0) {
            Toastr::error('Sorry! Your account is not permit to create parcel', 'warning!');

            return redirect()->back();
        }
        $edit_data = Parcel::where('id', $id)
            ->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')
            ->first();
        $merchants = Merchant::with('activeSubscription')->orderBy('id', 'DESC')->get();
        $delTowns = \App\Town::where('cities_id', $edit_data->delivery_cities_id)->get();

        return view('frontEnd.layouts.pages.agent.addparcel.edit', compact('edit_data', 'merchants', 'delTowns'));
    }

    public function parcelupdate(Request $request)
    {
        $agentsettings = \App\Agent::where('id', Session::get('agentId'))->first() ?? null;
        if ($agentsettings->agent_create_parcel == 0) {
            Toastr::error('Sorry! Your account is not permit to create parcel', 'warning!');
            
            return redirect()->back();
        }
        
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
            // 'pickupOrdropOff'=> 'required',
            // 'addressofSender'=> 'required',
            'deliverycity'   => 'required',
            'deliverytown'   => 'required',
            // 'vehicle_type'=> 'required',
        ]);
        
        $charge = \App\ChargeTarif::where('pickup_cities_id', $request->pickupcity)->where('delivery_cities_id', $request->deliverycity)->first();
        
        $town       = \App\Town::where('id', $request->deliverytown)->where('cities_id', $request->deliverycity)->first();
        $merchant   = Merchant::find($request->merchantId);
        $codAmt     = $request->cod ? remove_commas($request->cod) : 0;
        $packageAmt = $request->package_value ? remove_commas($request->package_value) : 0;
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $merchant->id)->where('is_active', 1)->first();
        
        
        if ($request->weight > 1 || $request->weight != null) {
            
            $extraweight = $request->weight - 1;
            
            $deliverycharge = ($charge->deliverycharge + $town->towncharge) + ($extraweight * $charge->extradeliverycharge);
            
            $weight = $request->weight;
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
            // 2 for pay on delivery
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
            $merchant = Merchant::find($request->merchantId);

            $insurance = ($packageAmt * $charge->insurance) / 100;
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

        // old

        $update_parcel = Parcel::find($request->hidden_id);

        // if ($request->payment_option == 1) {
        //     $merchant = Merchant::find($request->merchantId);

        //     if ($merchant->balance < $totalDelCharge) {
        //         session()->flash('message', 'Wallet Balance is low. Please
        //         top up.');

        //         return redirect()->back();
        //     }

        //     $merchant->balance = $merchant->balance - $totalDelCharge;
        //     $merchant->save();
        // }
        $update_parcel->invoiceNo          = $request->invoiceno;
        $update_parcel->merchantId         = $request->merchantId;
        $update_parcel->order_number       = $request->order_number;
        $update_parcel->cod                = $codAmt;
        $update_parcel->package_value      = $packageAmt;
        $update_parcel->tax                = $tax;
        $update_parcel->insurance          = $merchant->ins_cal_permission == 0 ? 0 : $insurance;
        $update_parcel->percelType         = $request->percelType;
        $update_parcel->recipientName      = $request->name;
        $update_parcel->recipientAddress   = $request->address;
        $update_parcel->recipientPhone     = $request->phonenumber;
        $update_parcel->productName        = $request->productName;
        $update_parcel->productQty         = $request->productQty;
        $update_parcel->productColor       = $request->productColor;
        $update_parcel->productWeight      = $request->weight;
        $update_parcel->reciveZone         = $request->reciveZone;
        $update_parcel->note               = $request->note ?? '';
        $update_parcel->deliveryCharge     = $deliverycharge;
        $update_parcel->codCharge          = $merchant->cod_cal_permission == 0 ? 0 : $codcharge;
        $update_parcel->merchantAmount     = $merchantAmount;
        $update_parcel->merchantDue        = $merchantDue;
        $update_parcel->orderType          = $request->orderType;
        $update_parcel->pickup_cities_id   = $request->pickupcity;
        $update_parcel->delivery_cities_id = $request->deliverycity;
        $update_parcel->pickup_town_id     = $request->pickuptown;
        $update_parcel->delivery_town_id   = $request->deliverytown;
        // agent commision

        // if ($agentsettings->commisiontype == 1) {
        //     $commision = $agentsettings->commision;
        // } else {
        //     $commision = ($agentsettings->commision * $deliverycharge) / 100;
        // }

        // $commision += $codAmt;

        $update_parcel->agentAmount = $codAmt;

        $update_parcel->update();
        // dd($update_parcel);


        if ($request->payment_option == 1) {
            $RemainTopup = RemainTopup::where('parcel_id', $request->hidden_id)->first();
            if ($RemainTopup) {
                $RemainTopup->amount      = $deliverycharge + $tax + $insurance;
                $RemainTopup->merchant_id = $request->merchantId;
                $RemainTopup->update();
            } else {
                RemainTopup::create([
                    'parcel_id'     => $update_parcel->id,
                    'parcel_status' => 1,
                    'merchant_id'   => $update_parcel->merchantId,
                    'amount'        => $deliverycharge + $tax + $insurance,
                ]);
            }

        }

        //Save to History table
        $parcel = Parcel::find($request->hidden_id);

        $history            = new History();
        $history->name      = $parcel->recipientName;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = $agentsettings->name;
        $history->status    = 'Parcel Edited By ' . $agentsettings->name;
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

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

        return back();
    }
}
