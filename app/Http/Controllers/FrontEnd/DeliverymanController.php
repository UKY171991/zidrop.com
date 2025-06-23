<?php
namespace App\Http\Controllers\FrontEnd;

use App\Deliverycharge;
use App\Deliveryman;
use App\Exports\RiderParcelExport;
use App\History;
use App\Http\Controllers\Controller;
use App\Mail\ParcelStatusUpdateEmail;
use App\Merchant;
use App\Parcel;
use App\Agent;
use App\Parcelnote;
use App\Parceltype;
use App\DeliverymanCommission;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Session;

class DeliverymanController extends Controller
{
    public function loginform()
    {
        return view('frontEnd.layouts.pages.deliveryman.login');
    }
    public function commission_history($id)
    {
        $deliverymanId = $id;
        return view('frontEnd.layouts.pages.deliveryman.commissionpayments', compact('deliverymanId'));
    }
    public function get_history($id)
    {
        // Base query
        $query = DeliverymanCommission::where('deliveryman_id', $id)->where('pay_status', 1)
            ->where('deliveryman_id', Session::get('deliverymanId'))
            ->with(['deliveryman'])
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
            $invoiceUrl = route('del.invoice_com_history', $value->id);
            $TotalInvoice =  count(explode(',', $value->parcel_ids));

            $datavalue = [
                $key + 1, // Serial number starts from 1
                optional($value->deliveryman)->name,
                number_format($value->deliveryman_commission, 2),
                date('d M Y', strtotime($value->date)) . '<br>' . date("g:i a", strtotime($value->created_at)),
                $TotalInvoice,
                '<td>
                    <ul class="action_buttons cust-action-btn">
                      <li>
                         <a class="btn btn-primary" href="'.$invoiceUrl.'" title="Invoice" >
                            <i class="fa fa-eye" ></i>
                         </a>
                      </li> 
                   </ul>
                </td>',
            ];


            $data['data'][] = $datavalue;
        }

        return response()->json($data);

    }

    public function invoice_com_history($id)
    {
        // Retrieve AgentCommission by ID and load related agent and parcels
        $DeliverymanCommission = DeliverymanCommission::where('id', $id)
            ->with(['deliveryman']) // Eager load the agent
            ->first(); // Get the first (and only) result by ID
    
        // Check if the commission exists
        if (!$DeliverymanCommission) {
            return redirect()->back()->with('error', 'Deliveryman Commission not found.');
        }
    
        // Now, you can access the parcels using the `parcels` attribute
        $parcels = $DeliverymanCommission->parcels; // This triggers the `getParcelsAttribute()` method
    
        // Pass the data to the view
        return view('frontEnd.layouts.pages.deliveryman.invoice_commission', compact('DeliverymanCommission', 'parcels'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);
        $checkAuth = Deliveryman::where('email', $request->email)
            ->first();
        if ($checkAuth) {
            if ($checkAuth->status == 0) {
                Toastr::warning('warning', 'Opps! your account has been suspends');
                return redirect()->back();
            } else {
                if (password_verify($request->password, $checkAuth->password)) {
                    $deliverymanId = $checkAuth->id;
                    Session::put('deliverymanId', $deliverymanId);
                    Toastr::success('success', 'Thanks , You are login successfully');
                    return redirect('deliveryman/dashboard');

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
        
        $deliveryman = Deliveryman::find(Session::get('deliverymanId'));

        $totalparcel = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->count();
        $totaldelivery = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['status' => 4])->count();
        $totalhold = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['status' => 5])->count();
        $totalcancel = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['status' => 9])->count();
        $returnpendin = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['status' => 6])->count();
        $returnmerchant = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['status' => 8])->count();

        // $totalamount = Parcel::where(function ($query) {
        //     $query->where('deliverymanId', Session::get('deliverymanId'));
        //     $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        // })->where(['status' => 4])
        //     ->sum('deliverymanAmount');

            $totalamount = Parcel::where(function ($query) {
                $query->where('deliverymanId', Session::get('deliverymanId'))
                      ->orWhere('pickupmanId', Session::get('deliverymanId'));
            })
            ->whereIn('status', [6, 4]) // Matching your second query's status filter
            ->sum('cod'); // Summing the 'cod' column instead of 'deliverymanAmount'



        $unpaidamount = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['deliverymanPaystatus' => 0])
            ->sum('deliverymanAmount');
        $paidamount = Parcel::where(function ($query) {
            $query->where('deliverymanId', Session::get('deliverymanId'));
            $query->orWhere('pickupmanId', Session::get('deliverymanId'));
        })->where(['deliverymanPaystatus' => 1])
            ->sum('deliverymanAmount');

            $outfordevilery = Parcel::where(['deliverymanId' => Session::get('deliverymanId'), 'status' => 3])->count();
            $delivered = Parcel::where(['deliverymanId' => Session::get('deliverymanId'), 'status' => 4])->count();
            $partialdelivery = Parcel::where(['deliverymanId' => Session::get('deliverymanId'), 'status' => 6])->count();
            $disputedpackages = Parcel::where(['deliverymanId' => Session::get('deliverymanId'), 'status' => 5])->count();
            $returtohub = Parcel::where(['deliverymanId' => Session::get('deliverymanId'), 'status' => 7])->count();

        return view('frontEnd.layouts.pages.deliveryman.dashboard', compact('totalparcel', 'totaldelivery', 'totalhold', 'totalcancel', 'returnpendin', 'returnmerchant', 'totalamount', 'unpaidamount', 'paidamount', 'deliveryman','outfordevilery', 'delivered', 'partialdelivery', 'disputedpackages', 'returtohub'));
    }

    public function parcels(Request $request)
    {
        // dd('all');
        $filter = $request->filter_id;
        // dd(Session::get('deliverymanId'));
        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->where('deliverymanId', Session::get('deliverymanId'))
            ->orWhere('pickupmanId', Session::get('deliverymanId'))
            ->orderBy('updated_at', 'DESC');
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

        $allparcel = $query->get();
        // dd($allparcel);
        $parceltype = [];
        $slug = 'all';

        //   return $allparcel;
        return view('frontEnd.layouts.pages.deliveryman.parcels', compact('allparcel', 'parceltype', 'slug'));
    }
    public function get_parcel_data(Request $request, $slug)
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
            $query = \App\Parcel::with([
                    'pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 
                    'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'
                ])
                ->where(function ($q) use ($request) {
                    if ($request->trackId) {
                        $q->where('trackingCode', $request->trackId);
                    } 
                    if ($request->phoneNumber) {
                        $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
                        $q->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
                    } 
                    if ($request->startDate && $request->endDate) {
                        $startDate = Carbon::parse($request->startDate)->startOfDay();
                        $endDate = Carbon::parse($request->endDate)->endOfDay();
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    }
                })
                ->where(function ($q) {
                    $q->where('deliverymanId', Session::get('deliverymanId'))
                      ->orWhere('pickupmanId', Session::get('deliverymanId'));
                })
                ->orderBy('updated_at', 'DESC');
        } else {
            $parceltype = Parceltype::where('slug', $slug)->first();
        
            $query = \App\Parcel::with([
                    'pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 
                    'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'
                ])
                ->where('status', $parceltype->id) // ✅ Apply status first
                ->where(function ($q) {
                    $q->where('deliverymanId', Session::get('deliverymanId'))
                      ->orWhere('pickupmanId', Session::get('deliverymanId'));
                })
                ->orderBy('updated_at', 'DESC');
        
            if ($request->trackId) {
                $query->where('trackingCode', $request->trackId);
            } 
            if ($request->phoneNumber) {
                $phoneNumber = preg_replace('/[\s\-\.\(\)]/', '', $request->phoneNumber); // Remove spaces, dashes, dots, parentheses
                $query->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(recipientPhone, ' ', ''), '-', ''), '.', ''), '(', '') LIKE ?", ["%{$phoneNumber}%"]);
            }
            if ($request->startDate && $request->endDate) {
                $startDate = Carbon::parse($request->startDate)->startOfDay();
                $endDate = Carbon::parse($request->endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        $count = $query->count();
        $allparcel = $query->offset($start)->limit($length)->get();

        $aparceltypes = Parceltype::limit(3)->get();
        $data = [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => [],
        ];

        foreach ($allparcel as $key => $value) {
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $merchantInfo = Merchant::find($value->merchantId);
            $parcelstatus = Parceltype::find($value->status);
            $merchantDetails = $value->getMerchantOrSenderDetails();
        

            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" data-parcel_status_update_sl="' . $parcelstatus->sl . '" name="parcel_id[]" form="myform"></form>';

            $datavalue[1] = $value->trackingCode;

                $datavalue[2] = '<ul><li class="m-1"><button class="btn-info" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-package_value = "' . number_format($value->package_value, 2) .'" data-cod = "' . number_format($value->cod, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
          
          
            if ($value->status != 8) {
                $datavalue[2] .= '<li class="m-1"><button class="btn-danger" href="#" id="sUpdateModal" title="Action" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"data-parcel_status_update_sl="' . $parcelstatus->sl . '"><i class="fa fa-sync-alt"></i></button></li>';
            }

            $datavalue[2] .= '<li class="m-1"><a class="btn btn-primary p-2" href="' . url('deliveryman/parcel/invoice/' . $value->id) . '" target="_blank"  title="Invoice"><i class="fas fa-list"></i></a></li>';

            $datavalue[2] .= '</ul>';

            $datavalue[3] = date('d M Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            if($value->parcel_source == 'p2p'){
                $datavalue[4] = 'P2P';
            }else{
                $datavalue[4] = $merchantDetails->companyName;
            }
            $datavalue[5] = $value->recipientName;
            $datavalue[6] = $value->recipientPhone;
           

            $datavalue[7] = $parcelstatus->title;
            $datavalue[8] = number_format($value->cod, 2);
            $datavalue[9] = number_format($value->deliveryCharge, 2);
            $datavalue[10] = number_format($value->tax, 2);
            $datavalue[11] = number_format($value->insurance, 2);
            $datavalue[12] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);
            $datavalue[13] = date('F d, Y', strtotime($value->updated_at)). '<br>' . date("g:i a", strtotime($value->updated_at));
            if ($value->merchantpayStatus == null) {
                $datavalue[14] = 'NULL';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[14] = 'Processing';
            } else {
                $datavalue[14] = 'Paid';
            }

            $parcelnote = Parcelnote::where('parcelId', $value->id)
                ->orderBy('id', 'DESC')
                ->first();
            $datavalue[15] = $merchantInfo->id ?? 'P2P';
           
 
            if ($value->pickupmanId && $value->deliverymanId == null){

                $datavalue[16] = 'Pickup Assign';
            } elseif($value->deliverymanId && $value->pickupmanId == null){

                $datavalue[16] = 'Deliveryman Assign';
            } elseif($value->deliverymanId && $value->deliverymanId){

                $datavalue[16] = 'Pickup Assign';
                $datavalue[16] = 'Deliveryman Assign';
            }
            
            if (!empty($parcelnote)) {
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
        $parceltype = Parceltype::where('slug', $slug)->first();

        $allparcel = \App\Parcel::select('*')
            ->where('deliverymanId', Session::get('deliverymanId'))
            ->where('deliverymanId', Session::get('deliverymanId'))
            ->orWhere('pickupmanId', Session::get('deliverymanId'))
            ->where('parcels.status', $parceltype->id)
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderBy('id', 'DESC')
            ->get();

        return view('frontEnd.layouts.pages.deliveryman.parcels', compact('allparcel', 'parceltype', 'slug'));
    }

    public function invoice($id)
    {
        $show_data = \App\Parcel::select('*')
            ->where('deliverymanId', Session::get('deliverymanId'))
            ->where('id', $id)
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderBy('id', 'DESC')
            ->first();

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.deliveryman.invoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');
            return redirect()->back();
        }
    }
    public function track(Request $request)
    {
        $trackparcel = Parcel::where('trackingCode', 'LIKE', '%' . $request->trackid . "%")
            ->where('deliverymanId', Session::get('deliverymanId'))
            ->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->first();

        if ($trackparcel) {
            $trackInfos = Parcelnote::where('parcelId', $trackparcel->id)->orderBy('id', 'ASC')->with('notes')->get();

            return view('frontEnd.layouts.pages.deliveryman.trackparcel', compact('trackparcel', 'trackInfos'));
        } else {
            return redirect()->back();
        }

    }
    public function statusupdate(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $parcel             = Parcel::find($request->hidden_id);
        $parcel->status     = $request->status;
        $parcel->updated_at = Carbon::now();
        $parcel->save();

        if ($request->note) {
            $note           = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note     = $request->note;
            $note->save();
        }
        if ($request->status == 3) {
            $parcel         = Parcel::find($request->hidden_id);
            $parcel->status = $request->status;
            $parcel->save();

            $codcharge              = 0;
            $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
            $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
            $parcel->codCharge      = $codcharge;
            $parcel->save();
        } elseif ($request->status == 4) {
            $parcel         = Parcel::find($request->hidden_id);
            $parcel->status = $request->status;
            $parcel->save();
            if ($request->note != null) {
                $note           = new Parcelnote();
                $note->parcelId = $request->hidden_id;
                $note->note     = 'Parcel delivered successfully';
                $note->save();
            }
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

            // $merchantinfo =Merchant::find($parcel->merchantId);
            //  $data = array(
            //  'contact_mail' => $merchantinfo->emailAddress,
            //  'trackingCode' => $parcel->trackingCode,
            // );
            //  $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@aschi.com.bd');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Percel Cancelled Notification');
            // });
        } elseif ($request->status == 6) {
            if ($parcel->payment_option == 2) {
                $charge      = \App\ChargeTarif::where('pickup_cities_id', $parcel->pickup_cities_id)->where('delivery_cities_id', $parcel->delivery_cities_id)->first();
                $codcharge   = ($request->partial_payment * $charge->codcharge) / 100;
                $parcel->cod = $request->partial_payment;

                $amount = $request->partial_payment - ($codcharge + $parcel->deliveryCharge);

                $parcel->merchantAmount = $amount;
                $parcel->merchantDue    = $amount;
                $parcel->codCharge      = $codcharge;
                $parcel->save();

            }
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

        } elseif ($request->status == 8) {
            $parcel                 = Parcel::find($request->hidden_id);
            $returncharge           = $parcel->deliveryCharge / 2;
            $parcel->merchantAmount = $parcel->merchantAmount - $returncharge;
            $parcel->merchantDue    = $parcel->merchantAmount - $returncharge;
            $parcel->deliveryCharge = $parcel->deliveryCharge + $returncharge;
            $parcel->save();
        }

        $pstatus = Parceltype::find($request->status);

        $pstatus = $pstatus->title;

        //Save to History table

        $deliveryman = Deliveryman::where('id', session('deliverymanId'))->first();

        $history            = new History();
        $history->name      = $parcel->recipientName;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = $deliveryman->name;
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
                Log::info('DeliveryMan Parcel status update mail error: ' . $exception->getMessage());
            }
        }

        Toastr::success('message', 'Parcel information update successfully!');
        return redirect()->back();
    }
    public function pickup()
    {
        $show_data = DB::table('pickups')
            ->where('pickups.deliveryman', Session::get('deliverymanId'))
            ->orderBy('pickups.id', 'DESC')
            ->select('pickups.*')
            ->get();
        $deliverymen = Deliveryman::where('status', 1)->get();
        return view('frontEnd.layouts.pages.deliveryman.pickup', compact('show_data', 'deliverymen'));
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

        Toastr::success('message', 'Pickup status update successfully!');
        return redirect()->back();
    }
    public function passreset()
    {
        return view('frontEnd.layouts.pages.deliveryman.passreset');
    }
    public function passfromreset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);
        $validDeliveryman = Deliveryman::Where('email', $request->email)
            ->first();
        if ($validDeliveryman) {
            $verifyToken                     = rand(111111, 999999);
            $validDeliveryman->passwordReset = $verifyToken;
            $validDeliveryman->save();
            Session::put('resetDeliverymanId', $validDeliveryman->id);

            $data = [
                'contact_mail' => $validDeliveryman->email,
                'verifyToken'  => $verifyToken,
            ];
            $send = Mail::send('frontEnd.layouts.pages.deliveryman.forgetemail', $data, function ($textmsg) use ($data) {
                $textmsg->from('e-tailing@zidrop.com');
                $textmsg->to($data['contact_mail']);
                $textmsg->subject('Forget password token');
            });
            return redirect('deliveryman/resetpassword/verify');
        } else {
            Toastr::error('Sorry! You have no account', 'warning!');
            return redirect()->back();
        }
    }
    public function saveResetPassword(Request $request)
    {
        // return "okey";
        $validDeliveryman = Deliveryman::find(Session::get('resetDeliverymanId'));
        if ($validDeliveryman->passwordReset == $request->verifyPin) {
            $validDeliveryman->password      = bcrypt(request('newPassword'));
            $validDeliveryman->passwordReset = null;
            $validDeliveryman->save();

            Session::forget('resetDeliverymanId');
            Session::put('deliverymanId', $validDeliveryman->id);
            Toastr::success('Wow! Your password reset successfully', 'success!');
            return redirect('deliveryman/dashboard');
        } else {
            return $request->verifyPin;
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect()->back();
        }

    }
    public function resetpasswordverify()
    {
        if (Session::get('resetDeliverymanId')) {
            return view('frontEnd.layouts.pages.deliveryman.passwordresetverify');
        } else {
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function logout()
    {
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');
        return redirect('deliveryman/logout');
    }
    public function export(Request $request)
    {
        return Excel::download(new RiderParcelExport(), 'parcel.xlsx');

    }

}
