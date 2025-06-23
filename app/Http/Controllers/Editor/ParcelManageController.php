<?php
namespace App\Http\Controllers\Editor;

use App\Agent;
use App\Agentpayment;
use App\Agentpaymentdetail;
use App\Codcharge;
use App\Deliverycharge;
use App\Deliveryman;
use App\Exports\MerchantPaymentExport;
use App\History;
use App\Http\Controllers\Controller;
use App\Mail\ParcelStatusUpdateEmail;
use App\Merchant;
use App\Merchantpayment;
use App\Merchantreturnpayment;
use App\Parcel;
use App\Parcelnote;
use App\Parceltype;
use App\RemainTopup;
use App\MerchantSubscriptions;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use DNS1D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel as MatwebsiteExcel;
use PDF;

class ParcelManageController extends Controller
{

    public function merchantsubshisto($id)
    {

        $SubsHistos = MerchantSubscriptions::with('plan', 'merchant')
        ->where('merchant_id', $id)
        ->orderByDesc('created_at')
        ->limit(20)
        ->get()
        ->map(function ($item) {
            $item->formatted_date = \Carbon\Carbon::parse($item->assign_time)->format('d/m/Y');
            $item->formatted_time = \Carbon\Carbon::parse($item->assign_time)->format('j M Y') . ' To ' . \Carbon\Carbon::parse($item->expired_time)->format('j M Y');
            return $item;
        });

        return view('backEnd.merchant.subshisto', compact('SubsHistos'));
    }
    public function merchantpaymentlist()
    {

        if (request()->filter_id == 1) {
            $show_data = Merchant::select(['id', 'companyName', 'paymentMethod'])->with(['parcels' => function ($query) {
                return $query->whereDate('updated_at', '>=', request()->startDate)->whereDate('updated_at', '<=', request()->endDate)->get();
            },
            ])->get();
        } else {

            $show_data = Merchant::select(['id', 'companyName', 'paymentMethod'])->with(['parcels' => function ($query) {
                return $query->get();
            },
            ])->get();
        }

        return view('backEnd.parcel.merchantpayment', compact('show_data'));
    }

    public function agentpaymentrequest()
    {
        $show_data = Agentpayment::orderBy('status', 'ASC')->orderBy('created_at', 'DESC')->get();

        return view('backEnd.parcel.agentpayment', compact('show_data'));
    }

    public function agentpaymentconfirm(Request $request)
    {

        foreach ($request->payment_id as $paymentId) {
            $payment          = Agentpayment::find($paymentId);
            $payment->done_by = auth()->user()->name;
            $payment->status  = 1;
            $payment->save();

            $parcels_id = Agentpaymentdetail::where('paymentId', $paymentId)->pluck('parcelId');

            foreach ($parcels_id as $parcelId) {
                $parcel                 = Parcel::find($parcelId);
                $parcel->agentPaystatus = 1;
                $parcel->timestamps     = false;
                $parcel->save();
                $parcel->timestamps = true;
            }
        }
        return redirect()->back();

    }

    public function agentpaymentinvoice(Request $request)
    {
        $payment = Agentpayment::where('id', $request->paymentId)->first();

        $parcelId = Agentpaymentdetail::where('paymentId', $request->paymentId)
            ->pluck('parcelId')
            ->toArray();
        $parcels = DB::table('parcels')->whereIn('id', $parcelId)->get();

        $agentInfo = Agent::find($payment->agentId);

        return view('backEnd.parcel.invoice_agentpayment', compact('parcels', 'agentInfo', 'payment'));
    }

    public function exportMerchantPaymentList()
    {

        $selected_merchants = json_decode(request()->merchants);

        if (request()->filter_id == 1) {
            $show_data = Merchant::with(['parcels' => function ($query) {
                return $query->whereDate('updated_at', '>=', request()->startDate)->whereDate('updated_at', '<=', request()->endDate)->get();
            },
            ])
                ->whereIn('id', $selected_merchants)
                ->get();
        } else {

            $show_data = Merchant::with(['parcels' => function ($query) {
                return $query->get();
            },
            ])
                ->whereIn('id', $selected_merchants)
                ->get();
        }

        if (request()->type == 'csv') {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'zidrop_merchants_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.csv');
        } else {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'zidrop_merchants_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.xlsx');
        }

    }
    public function exportMerchantPaymentListpdf()
    {

        $selected_merchants = json_decode(request()->merchants);

        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();

        $show_data = Merchant::with(['parcels' => function ($query) use ($parceltype) {
            return $query->where('status', '=', $parceltype->id)->where('deliveryCharge', '>', 0)->where('pay_return', 0);
        },
        ])->whereIn('id', $selected_merchants)->get();

        foreach ($show_data as $parcel) {
            $charge = 0;

            if (count($parcel->parcels) > 0) {

                foreach ($parcel->parcels as $p) {
                    $charge += $p->deliveryCharge + $p->tax + $p->insurance;

                }

                $parcel['charge'] = $charge;
            }

        }

        if (request()->type == 'csv') {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'zidrop_merchants_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.csv');
        } else if (request()->type == 'pdf') {

            //return view('backEnd.parcel.merchantpaymentpdf', compact('show_data'));

            $pdf = PDF::loadView('backEnd.parcel.merchantpaymentpdf', compact('show_data'));
            return $pdf->download('Returned To Merchant Payment - ' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g.i.A') . '.pdf');

            // return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'zidrop_merchants_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.pdf');
        } else {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'zidrop_merchants_settlement_' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.xlsx');
        }

    }
    public function exportMerchantreturnPaymentList()
    {

        $selected_merchants = json_decode(request()->merchants);

        $show_data = Merchant::with(['parcels' => function ($query) {
            return $query->get();
        },
        ])
            ->whereIn('id', $selected_merchants)
            ->get();

        if (request()->type == 'csv') {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'ZiDrop- Return Invoice - ' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.csv');
        } else if (request()->type == 'pdf') {
            $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();

            $show_data = Merchant::with(['parcels' => function ($query) use ($parceltype) {
                return $query->where('status', '=', $parceltype->id)->where('deliveryCharge', '>', 0)->where('pay_return', 0);
            },
            ])->whereIn('id', $selected_merchants)->get();
            foreach ($show_data as $parcel) {
                $charge = 0;

                if (count($parcel->parcels) > 0) {

                    foreach ($parcel->parcels as $p) {
                        $charge += $p->deliveryCharge + $p->tax + $p->insurance;
                    }

                    $parcel['charge'] = $charge;
                }

            }
            // return view('backEnd.parcel.merchantpaymentpdf', compact('show_data'));

            $pdf = PDF::loadView('backEnd.parcel.merchantpaymentpdf', compact('show_data'));
            return $pdf->download('Returned To Merchant Payment - ' . Carbon::now()->format('j-M-Y') . '.pdf');

            // return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'bulk-transfer- ZiDrop Merchant Payment - ' . Carbon::now()->format('j-M-Y')  . '_at_' . Carbon::now()->format('g:i A') . '.pdf');
        } else {
            return MatwebsiteExcel::download(new MerchantPaymentExport($show_data), 'Returned To Merchant Payment - ' . Carbon::now()->format('j-M-Y') . '_at_' . Carbon::now()->format('g:i A') . '.xlsx');
        }

    }

    public function merchantreturnlist()
    {
        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();
        $marchents  = Merchant::select(['id', 'companyName', 'paymentMethod'])->with(['parcels' => function ($query) use ($parceltype) {
            return $query->where('status', '=', $parceltype->id)->where('deliveryCharge', '>', 0)->where('pay_return', 0);
        },
        ])->get();

        foreach ($marchents as $parcel) {
            $charge = 0;

            if (count($parcel->parcels) > 0 && $parcel->pay_return == 0) {

                foreach ($parcel->parcels as $p) {

                    $charge += $p->deliveryCharge + $p->tax + $p->insurance;

                }

                $parcel['charge'] = $charge;
            }

        }

        $marchents = $marchents->where('charge', '>', 0);

        return view('backEnd.parcel.merchantReturnPayment', compact('marchents'));
    }

    public function returnpaymenthistory($id)
    {
        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();
        $marchents  = Merchant::where('id', $id)->select(['id', 'companyName', 'paymentMethod'])->with(['parcels' => function ($query) use ($parceltype) {
            return $query->where('status', '=', $parceltype->id)->where('deliveryCharge', '>', 0)->where('pay_return', 0);
        },
        ])->get();

        foreach ($marchents as $parcel) {
            $charge = 0;

            if (count($parcel->parcels) > 0 && $parcel->pay_return == 0) {

                foreach ($parcel->parcels as $p) {
                    $charge += $p->deliveryCharge + $p->tax + $p->insurance;

                }

                $parcel['charge'] = $charge;
            }

        }

        $marchents = $marchents->where('charge', '>', 0);

        return view('backEnd.parcel.merchantwiseReturnPayment', compact('marchents'));
    }

    /**
     * Parcel return to marchent
     */
    // public function merchantconfirmreturnpayment(Request $request)
    // {
    //     // return $request;
    //     $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();
    //     $parcelIds = Parcel::whereIn('merchantId', $request->marchent_id)->where('status', $parceltype->id)->where('pay_return', 0)->get();

    //     $parcels = Parcel::whereIn('merchantId', $request->marchent_id)->where('status', $parceltype->id)->where('pay_return', 0)->get();
    //     foreach ($parcels as $parcel) {
    //         $parcel->pay_return = 1;
    //         $parcel->timestamps = false;
    //         $parcel->save();
    //         $parcel->timestamps = true;

    //     }

    //     foreach ($parcelIds as $parcel) {
    //         $returnpayment = new Merchantreturnpayment();
    //         $returnpayment->merchantId = $parcel->merchantId;
    //         $returnpayment->parcelId = $parcel->id;
    //         $returnpayment->done_by = auth()->user()->name;
    //         $returnpayment->save();
    //     }
    //     Toastr::success('message', 'Merchant Returns Paid.');

    //     return back();
    // }
    public function merchantconfirmreturnpayment(Request $request)
    {
        // Fetch parcel type for return to merchant
        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();

        // Fetch parcels for the selected merchants that need return payment processing
        $parcels = Parcel::whereIn('merchantId', $request->marchent_id)
            ->where('status', $parceltype->id)
            ->where('pay_return', 0)
            ->get();

        // Iterate through each parcel to mark return payment and deduct charge
        foreach ($parcels as $parcel) {
            // Mark as returned
            $parcel->pay_return = 1;

            // Deduct return charge from merchantDue
            $returnCharge = $parcel->deliveryCharge + $parcel->tax + $parcel->insurance;
            $parcel->merchantDue -= $returnCharge;
            // dd($parcel->merchantDue );

            // Disable timestamps temporarily
            $parcel->timestamps = false;
            $parcel->save();
            $parcel->timestamps = true;

            // Log return payment
            $returnpayment             = new Merchantreturnpayment();
            $returnpayment->merchantId = $parcel->merchantId;
            $returnpayment->parcelId   = $parcel->id;
            $returnpayment->done_by    = auth()->user()->name;
            $returnpayment->save();
        }

        // Notify success
        Toastr::success('Merchant Returns Paid.');

        return back();
    }

    public function merchantconfirmpayment(Request $request)
    {
        /*
        if ($request->startDate && $request->endDate) {
        $parcels = Parcel::whereIn('merchantId', $request->parcel_id)->whereDate('updated_at', '>=', request()->startDate)
        ->whereDate('updated_at', '<=', request()->endDate)->where('merchantpayStatus', null)->where('status', 4)->get();

        foreach ($parcels as $parcel) {
        if ($parcel->status == 4 || $parcel->status == 6 || $parcel->status == 10) {
        $due                       = $parcel->merchantDue;
        $parcel->merchantDue       = 0;
        $parcel->merchantpayStatus = 1;
        $parcel->merchantPaid      = $due;
        $parcel->save();

        $payment = new Merchantpayment();
        $payment->merchantId = $parcel->merchantId;
        $payment->parcelId = $parcel->id;
        $payment->done_by = auth()->user()->name;
        $payment->save();
        }
        }

        } else {
        $parcels = Parcel::whereIn('merchantId', $request->parcel_id)->where('merchantpayStatus', null)->where('status', 4)->get();

        foreach ($parcels as $parcel) {
        if ($parcel->status == 4 || $parcel->status == 6 || $parcel->status == 10) {
        $due                       = $parcel->merchantDue;
        $parcel->merchantDue       = 0;
        $parcel->merchantpayStatus = 1;
        $parcel->merchantPaid      = $due;
        $parcel->save();

        $payment = new Merchantpayment();
        $payment->merchantId = $parcel->merchantId;
        $payment->parcelId = $parcel->id;
        $payment->done_by = auth()->user()->name;
        $payment->save();
        }
        }

        }
         */

        $parcels = Parcel::whereIn('merchantId', $request->parcel_id)->where('merchantpayStatus', null)->get();

        foreach ($parcels as $parcel) {

            if ($parcel->status == 4 || $parcel->status == 6) {
                $due                       = $parcel->merchantDue;
                $parcel->merchantDue       = 0;
                $parcel->merchantpayStatus = 1;
                $parcel->merchantPaid      = $due;
                // disable for auto update date or timestamp
                $parcel->timestamps = false;
                $parcel->save();
                // enable for auto update date or timestamp
                $parcel->timestamps = true;

                $payment             = new Merchantpayment();
                $payment->merchantId = $parcel->merchantId;
                $payment->parcelId   = $parcel->id;
                $payment->done_by    = auth()->user()->name;
                $payment->save();
            }

        }

        Toastr::success('message', 'Merchant Due Paid.');

        return back();

    }

    public function merchantPaymentDetails($id)
    {
        $data             = [];
        $data['merchant'] = Merchant::where('id', $id)->with('parcels')->first();

        return view('backEnd.parcel.mercant-payment-details', $data);
    }

    public function parcel(Request $request)
    {
        session()->forget('OLDTrackingCodesQRscans');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
        $slug = $request->slug;

        if ($slug === 'all') {
            $parceltype = '';
        } else {
            $parceltype = Parceltype::where('slug', $slug)->first();
        }

        return view('backEnd.parcel.parcel', compact('slug', 'parceltype'));
    }

    public function get_parcel_data(Request $request, $slug)
    {

        session()->forget('OLDTrackingCodesQRscans');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
        // dd($request->all());
        session()->forget('OLDTrackingCode');
        $parceltype = Parceltype::where('slug', $slug)->first();
        if ($slug === 'all') {
            $parceltype = '';
        } else {
            $parceltype = Parceltype::where('slug', $slug)->first();
        }

        if ($request->slug == 'return-to-merchant') {
            $canEdit = false;
        } else {
            $canEdit = true;
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

        $length = 10;

        if (isset($request->length)) {
            $length = $request->length;
        }
        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->orderBy('updated_at', 'DESC');

        if ($request->trackId) {
            $query->where('trackingCode', $request->trackId);
        } 
        if ($parceltype) {
            $query->where('status', $parceltype->id);
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

        $count     = $query->count();
        $show_data = $query->offset($start)->limit($length)->get();
        $agents    = DB::table('agents')->get();

        // Data table
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($show_data as $key => $value) {
            $parcelstatus = Parceltype::find($value->status);

            // $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" name="parcel_id[]" form="myform"> </form>';
            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" name="parcel_id[]" form="myform"></form>';

            $datavalue[1] = $value->trackingCode;
            // New data
            $merchant        = Merchant::find($value->merchantId);
            $agentInfo       = Agent::find($value->agentId);
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $pickupmanInfo   = Deliveryman::find($value->pickupmanId);
            $datavalue[2]    = '<ul class="action_buttons cust-action-btn">';

            if (Auth::user()->role_id <= 2) {

                if ($canEdit) {
                    $datavalue[2] .= '<li class="m-1"><a href="' . url('editor/parcel/edit/' . $value->id) . '" class="edit_icon"><i class="fa fa-edit"></i></a></li>';
                }

                $datavalue[2] .= '<li class="m-1"><a class="edit_icon anchor" target="_blank" href="' . url('editor/parcel/invoice/' . $value->id) . '" title="Invoice"><i class="fa fa-list"></i></a></li>';
            }

            if (Auth::user()->role_id <= 3 && $canEdit) {
                $datavalue[2] .= '<li class="m-1"><button class="thumbs_up" title="Action" id="sUpdateModal" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"><i class="fa fa-pencil"></i></button></li>';
            }

            if (Auth::user()->role_id <= 2) {
                $datavalue[2] .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcelh" data-id="' . $value->id . '" title="History"><i class="fas fa-history"></i></button></li>';
            }
            $merchantDetails = $value->getMerchantOrSenderDetails();

            // if($value->parcel_source == 'p2p'){
            //     $datavalue[2] .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' . $value->p2pParcel->sender_name .  '" data-type="'. 'p2p' . '" data-phonenumber = "' . $value->p2pParcel->sender_mobile . '" data-emailaddress = "' . $value->p2pParcel->sender_email . '" data-companyname = "' . 'P2P' . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title. '/'. $value->pickuptown->title .'" data-delivery = "' . $value->deliverycity->title. '/'. $value->deliverytown->title .'" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }else{
            $datavalue[2] .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number .'" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }

            $datavalue[2] .= '</ul>';
            $datavalue[3] = $merchant->id ?? 'P2P';
            $datavalue[4] = date('d M Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            if ($value->parcel_source == 'p2p') {
                $datavalue[5] = $value->p2pParcel->sender_name;
            } else {
                $datavalue[5] = $merchantDetails->companyName . '<br>(' . $merchantDetails->pickLocation . ')<br>(' . ($merchantDetails->phoneNumber) . ')';
            }
            $datavalue[6] = $value->recipientName;
            $datavalue[7] = $value->deliverycity->title . '/' . $value->deliverytown->title;
            $datavalue[8] = $value->recipientAddress;
            $datavalue[9] = $value->recipientPhone;

            if ($value->pickupmanId) {
                $datavalue[10] = '<button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '">' . $pickupmanInfo->name . '</button>';
            } else {
                $datavalue[10] = '<button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '">Asign</button>';
            }

            if ($value->deliverymanId) {
                $datavalue[11] = '<button class="btn btn-info" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-deliverymanidselectvalue="' . $value->deliverymanId . '">' . $deliverymanInfo->name . '</button>';
            } else {
                $datavalue[11] = '<button class="btn btn-primary" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-deliverymanidselectvalue="0">Asign</button>';
            }

            if ($value->agentId) {

                $datavalue[12] = '<button class="btn btn-success" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-agentis_selecetvalue="' . $value->agentId . '">' . $agentInfo->name . '</button>';
            } else {
                $datavalue[12] = '<button class="btn btn-primary" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-agentis_selecetvalue="0">Asign</button>';
            }

            $datavalue[13] = date('d M Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at));
            $datavalue[14] = $parcelstatus->title;
            $datavalue[15] = number_format($value->cod, 2);
            $datavalue[16] = number_format($value->package_value, 2);
            $datavalue[17] = number_format($value->deliveryCharge, 2);
            $datavalue[18] = number_format($value->codCharge, 2);
            $datavalue[19] = number_format($value->tax, 2);
            $datavalue[20] = number_format($value->insurance, 2);
            $datavalue[21] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);

            if ($value->pay_return == 0) {
                $datavalue[22] = '<div class="text-danger">' . number_format($value->deliveryCharge, 2) . '</div>';
            } else {
                $datavalue[22] = '<div class="text-success"> Paid </div>';
            }

            if ($value->merchantpayStatus == null) {
                $datavalue[23] = '<div class="text-danger"> NULL </div>';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[23] = '<div class="text-warning"> Processing </div>';
            } else {
                $datavalue[23] = '<div class="text-success"> Paid </div>';
            }

            array_push($data['data'], $datavalue);

        }

        return $data;

        // return view('backEnd.parcel.parcel', compact('show_data', 'parceltype', 'canEdit', 'agents'));
    }
    public function allparcel(Request $request)
    {
        session()->forget('OLDTrackingCodesQRscans');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
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

        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderByDesc('id');

        if ($request->trackId) {
            $query->where('trackingCode', $request->trackId);
        } elseif ($request->merchantId) {
            $query->where('merchantId', $request->merchantId);
        } elseif ($request->UpStatusArray) {
            $query->whereIn('status', $request->UpStatusArray);
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
        } elseif ($request->upstartDate && $request->upendDate) {
            $startDate = Carbon::parse($request->upstartDate)->startOfDay();
            $endDate   = Carbon::parse($request->upendDate)->endOfDay();
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $count = $query->count();

        $show_data = $query->offset($start)->limit($length)->get();

        return view('backEnd.parcel.allparcel', compact('show_data'));
    }
    public function get_parcel_data_all(Request $request)
    {

        session()->forget('OLDTrackingCodesQRscans');
        $OLDTrackingCodesQRscans = [];
        session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
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
        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderBy('updated_at', 'DESC');

        if ($request->trackId) {
            $query->where('trackingCode', $request->trackId);
        } 
        if ($request->merchantId) {
            $query->where('merchantId', $request->merchantId);
        } 
        if ($request->UpStatusArray) {
            $query->whereIn('status', $request->UpStatusArray);
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

        $count = $query->count();

        $show_data = $query->offset($start)->limit($length)->get();

        // Data table
        $data = [
            'draw'            => $draw,
            'recordsTotal'    => $count,
            'recordsFiltered' => $count,
            'data'            => [],
        ];

        foreach ($show_data as $key => $value) {

            $parcelstatus = Parceltype::find($value->status);
            // $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" name="parcel_id[]" form="myform"> </form>';
            $datavalue[0] = '<input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" data-status="' . $parcelstatus->id . '" name="parcel_id[]" form="myform"></form>';
            $datavalue[1] = $value->trackingCode;

            // New data
            $merchant        = Merchant::find($value->merchantId);
            $agentInfo       = Agent::find($value->agentId);
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $pickupmanInfo   = Deliveryman::find($value->pickupmanId);
            $datavalue[2]    = '<ul class="action_buttons cust-action-btn">';

            if (Auth::user()->role_id <= 2) {
                $datavalue[2] .= '<li class="m-1">';

                if ($value->status == 8 && \Illuminate\Support\Facades\Auth::user()->role_id == 1) {
                    $datavalue[2] .= '<a href="' . url('editor/parcel/edit/' . $value->id) . '" class="edit_icon"><i class="fa fa-edit"></i></a>';
                } elseif ($value->status != 8 && \Illuminate\Support\Facades\Auth::user()->role_id <= 3) {
                    $datavalue[2] .= '<a href="' . url('editor/parcel/edit/' . $value->id) . '" class="edit_icon"><i class="fa fa-edit"></i></a>';
                }

                $datavalue[2] .= '</li>';
            }

            if (Auth::user()->role_id <= 3) {
                $datavalue[2] .= '<li class="m-1">';

                if ($value->status == 8 && \Illuminate\Support\Facades\Auth::user()->role_id == 1) {
                    $datavalue[2] .= '<button class="thumbs_up" title="Action" id="sUpdateModal" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"><i class="fa fa-pencil"></i></button>';
                } else
                if ($value->status != 8 && \Illuminate\Support\Facades\Auth::user()->role_id <= 3) {
                    $datavalue[2] .= '<button class="thumbs_up" title="Action" id="sUpdateModal" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"><i class="fa fa-pencil"></i></button>';
                }

                $datavalue[2] .= '</li>';
            }
            // if($value->parcel_source == 'p2p'){
            //     $datavalue[2] .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' . $value->p2pParcel->sender_name .  '" data-type="'. 'p2p' . '" data-phonenumber = "' . $value->p2pParcel->sender_mobile . '" data-emailaddress = "' . $value->p2pParcel->sender_email . '" data-companyname = "' . 'P2P' . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title. '/'. $value->pickuptown->title .'" data-delivery = "' . $value->deliverycity->title. '/'. $value->deliverytown->title .'" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }else{
            $datavalue[2] .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '" data-cod = "' . number_format($value->cod, 2) . '" data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '"><i class="fa fa-eye"></i></button></li>';
            // }

            if (Auth::user()->role_id <= 2) {
                $datavalue[2] .= '<li class="m-1"><button class="edit_icon " href="#" id="merchantParcelh" data-id="' . $value->id . '" title="History"><i class="fas fa-history"></i></button></li>';
            }

            $datavalue[2] .= '<li class="m-1">';
            $datavalue[2] .= '<a class="edit_icon anchor" target="_blank" href="' . url('editor/parcel/invoice/' . $value->id) . '" title="Invoice"><i class="fa fa-list"></i></a>';
            $datavalue[2] .= '</li>';

            if (Auth::user()->role_id == 1) {
                $datavalue[2] .= '<li class="m-1">';
                $datavalue[2] .= '<form action="' . url('/editor/parcel/delete/' . $value->id) . '" method="post">';
                $datavalue[2] .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $datavalue[2] .= '<input type="hidden" name="_method" value="delete">';
                $datavalue[2] .= '<button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure want to delete this item?\')"><i class="fa fa-trash"></i></button>';
                $datavalue[2] .= '</form>';
                $datavalue[2] .= '</li>';

            }

            $datavalue[2] .= '</ul>';

            $datavalue[3] = $merchant->id;
            $datavalue[4] = date('d M Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at));
            $datavalue[5] = $merchant->companyName . '<br>(' . $merchant->pickLocation . ')<br>(' . $merchant->phoneNumber . ')';
            $datavalue[6] = $value->recipientName;
            $datavalue[7] = $value->deliverycity->title . '/' . $value->deliverytown->title;
            $datavalue[8] = $value->recipientAddress;

            $datavalue[9] = $value->recipientPhone;

            if ($value->pickupmanId) {
                $datavalue[10] = '<button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '">' . $pickupmanInfo->name . '</button>';
            } else {
                $datavalue[10] = '<button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '">Asign</button>';
            }

            if ($value->deliverymanId) {
                $datavalue[11] = '<button class="btn btn-info" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '" data-deliverymanidselectvalue="' . $value->deliverymanId . '">' . $deliverymanInfo->name . '</button>';
            } else {
                $datavalue[11] = '<button class="btn btn-primary" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '" data-deliverymanidselectvalue="0">Asign</button>';
            }

            if ($value->agentId) {
                $datavalue[12] = '<button class="btn btn-success" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '" data-agentis_selecetvalue="' . $value->agentId . '">' . $agentInfo->name . '</button>';
            } else {
                $datavalue[12] = '<button class="btn btn-primary" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchant->phoneNumber . '" data-agentis_selecetvalue="0">Asign</button>';
            }

            $datavalue[13] = date('F d, Y', strtotime($value->updated_at));
            $datavalue[14] = Parceltype::find($value->status)->title;
            $datavalue[15] = number_format($value->cod, 2);
            $datavalue[16] = number_format($value->package_value, 2);
            $datavalue[17] = number_format($value->deliveryCharge, 2);
            $datavalue[18] = number_format($value->codCharge, 2);
            $datavalue[19] = number_format($value->tax, 2);
            $datavalue[20] = number_format($value->insurance, 2);
            $datavalue[21] = number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2);

            if ($value->pay_return == 0) {
                $datavalue[22] = '<div class="text-danger">' . number_format($value->deliveryCharge, 2) . '</div>';
            } else {
                $datavalue[22] = '<div class="text-success"> Paid </div>';
            }

            if ($value->merchantpayStatus == null) {
                $datavalue[23] = '<div class="text-danger"> NULL </div>';
            } elseif ($value->merchantpayStatus == 0) {
                $datavalue[23] = '<div class="text-warning"> Processing </div>';
            } else {
                $datavalue[23] = '<div class="text-success"> Paid </div>';
            }

            array_push($data['data'], $datavalue);

        }

        return $data;
    }

    public function parceldelete(Request $request, $id)
    {
        $parcel = Parcel::findOrFail($id);
        $parcel->delete();
        Toastr::success('message', 'Parcel deleted successfully!');

        return redirect()->back();
    }

    public function invoice($id)
    {
        $query = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->where('id', $id)
            ->orderBy('id', 'DESC');

        $show_data = $query->first();

        return view('backEnd.parcel.invoice', compact('show_data'));
    }

    public function pdf($id)
    {
        $query = \App\Parcel::select('*')
            ->where('id', $id)
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->orderByDesc('id');

        $show_data = $query->first();

        $Qrcode2 = DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 1.4, 50, 'black', false);
        //$Qrcode2 = DNS2D::getBarcodeSVG($show_data->trackingCode, 'QRCODE', 2, 2);
        $Qrcode2 = str_replace('<?xml version="1.0" standalone="no"?>', '', $Qrcode2);

        $Qrcode4 = DNS1D::getBarcodeSVG($show_data->trackingCode, 'C128', 1.4, 50, 'black', false);
        //$Qrcode4 = DNS2D::getBarcodeSVG($show_data->trackingCode, 'QRCODE', 4, 4);
        $Qrcode4 = str_replace('<?xml version="1.0" standalone="no"?>', '', $Qrcode4);

        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");

        $pdf = new \Mpdf\Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'orientation'       => 'P',
            'margin_top'        => '5',
            'margin_bottom'     => '5',
            'margin_left'       => '5',
            'margin_right'      => '5',
            'default_font_size' => '10',
            'default_font'      => 'sans-serif',
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
            'margin_footer'     => '0',

        ]);
        $pdf->WriteHTML('');

        $view = 'backEnd.parcel.pdf';
        //$data = compact('show_data');
        $data = compact('show_data', 'Qrcode2', 'Qrcode4');

        $html = view($view, $data);
        $pdf->WriteHTML($html);
        $pdf->Output($show_data->trackingCode . '-invoice.pdf', 'I');

        return asset($show_data->trackingCode . '-invoice.pdf');
    }

    public function agentasign(Request $request)
    {
        $this->validate($request, [
            'agentId' => 'required',
        ]);
        $parcel          = Parcel::find($request->hidden_id);
        $parcel->agentId = $request->agentId;
        $parcel->save();

        //Save to History table

        $pstatus = Parceltype::find($parcel->status);

        $pstatus = $pstatus->title;

        $agentInfo = Agent::find($parcel->agentId);

        $history            = new History();
        $history->name      = "Customer: " . $parcel->recipientName . "<br><b>(Agent: )</b>" . $agentInfo->name;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = auth()->user()->name;
        $history->status    = '';
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

        // // agent commision
        // $agentInfo = Agent::find($parcel->agentId);

        // if ($agentInfo->commisiontype == 1) {
        //     $commision = $agentInfo->commision;
        // } else {
        //     $commision = ($agentInfo->commision * $parcel->deliveryCharge) / 100;
        // }

        // $commision += $parcel->cod;

        $parcel->agentAmount = $parcel->cod;
        $parcel->save();

        if ($request->note) {
            $note           = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note     = $request->note;
            $note->save();
        }

        Toastr::success('message', 'A agent asign successfully!');

        return redirect()->back();
    }

    public function pickupmanasign(Request $request)
    {
        $this->validate($request, [
            'pickupmanId' => 'required',
        ]);
        $parcel              = Parcel::find($request->hidden_id);
        $parcel->pickupmanId = $request->pickupmanId;
        $parcel->save();

        $pstatus = Parceltype::find($request->status);
        $pstatus = "Same as previous status.";

        //Save to History table

        $pstatus = Parceltype::find($parcel->status);

        $pstatus = $pstatus->title;

        $deliverymanInfo = Deliveryman::find($parcel->pickupmanId);

        $history            = new History();
        $history->name      = "Customer: " . $parcel->recipientName . "<br><b>(Pickupman: )</b>" . $deliverymanInfo->name;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = auth()->user()->name;
        $history->status    = '';
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

        if ($request->note) {
            $note           = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note     = $request->note;
            $note->save();
        }

        Toastr::success('message', 'A Pickupman asign successfully!');

        return redirect()->back();
        $deliverymanInfo = Deliveryman::find($parcel->pickupmanId);

    }

    public function deliverymanasign(Request $request)
    {
        $this->validate($request, [
            'deliverymanId' => 'required',
        ]);
        $parcel                = Parcel::find($request->hidden_id);
        $parcel->deliverymanId = $request->deliverymanId;
        $parcel->status        = 3;
        $parcel->save();

        // agent commision
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);

        if ($deliverymanInfo->commisiontype == 1) {
            $commision = $deliverymanInfo->commision;
        } else {
            $commision = ($deliverymanInfo->commision * $parcel->deliveryCharge) / 100;
        }

        $parcel->deliverymanAmount = $commision;
        $parcel->save();

        if ($request->note) {
            $note           = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note     = $request->note;
            $note->save();
        }

        //Save to History table

        $pstatus = Parceltype::find($parcel->status);

        $pstatus = $pstatus->title;

        $deliverymanInfo = Deliveryman::find($request->deliverymanId);

        $history            = new History();
        $history->name      = "Customer: " . $parcel->recipientName . "<br><b>(Deleveryman: )</b>" . $deliverymanInfo->name;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = auth()->user()->name;
        $history->status    = '';
        $history->note      = $request->note;
        $history->date      = $parcel->updated_at;
        $history->save();

        Toastr::success('message', 'A deliveryman asign successfully!');

        return redirect()->back();
    }

    public function bulkdeliverymanAssign(Request $request)
    {

        $request->validate([
            'agentId'   => 'required',
            'parcel_id' => 'required',
        ]);

        $parcels_id = $request->parcel_id;

        if (substr($parcels_id, 0, 1) == ',') {
            $parcels_id = substr($parcels_id, 1);
        }

        $parcels_id = explode(",", $parcels_id);
        $asigntype  = $request->asigntype;

        if ($request->btn == "rider") {
            if ($asigntype == 1) {

                foreach ($parcels_id as $parcel_id) {
                    $parcel              = Parcel::find($parcel_id);
                    $parcel->pickupmanId = $request->deliverymanId;
                    $parcel->save();
                }

            } else {

                foreach ($parcels_id as $parcel_id) {
                    $parcel                = Parcel::find($parcel_id);
                    $parcel->deliverymanId = $request->deliverymanId;
                    $parcel->status        = 3;
                    $parcel->save();
                }

            }

            if ($asigntype == 1) {
                $note           = new Parcelnote();
                $note->parcelId = $parcel_id;
                $note->note     = "Pickup Man Asign";
                $note->save();
            } else {
                $note           = new Parcelnote();
                $note->parcelId = $parcel_id;
                $note->note     = "Delivery Man Asign";
                $note->save();
            }

            return redirect()->back();
        } elseif ($request->btn == "agent") {
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
                $history->done_by   = auth()->user()->name;
                $history->status    = 'Transfer To Hub';
                $history->note      = 'In Transit To Delivery Facility';
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
                            Mail::to($validMerchant->emailAddress)->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                        }
                    } catch (\Exception $exception) {
                        Log::info('Parcel status update mail error: ' . $exception->getMessage());
                    }
                }

            }

            Toastr::success('message', 'Transfer to Hub successfully!');

            return redirect()->back();
        }

    }
    public function returntohub(Request $request)
    {

        $request->validate([
            'agentId'          => 'required',
            'return_parcel_id' => 'required',
        ]);
        $parcels_id = $request->return_parcel_id;

        if (substr($parcels_id, 0, 1) == ',') {
            $parcels_id = substr($parcels_id, 1);
        }

        $parcels_id = explode(",", $parcels_id);
        $asigntype  = $request->asigntype;

        foreach ($parcels_id as $parcel_id) {
            $parcel = Parcel::find($parcel_id);

            $parcel->agentId = $request->agentId;
            $parcel->status  = 7;
            $parcel->save();

            $note           = new Parcelnote();
            $note->parcelId = $parcel_id;
            $note->note     = "The Parcel Return To Origin Hub";
            $note->save();

            // history

            $history            = new History();
            $history->name      = $parcel->recipientName;
            $history->parcel_id = $parcel->id;
            $history->done_by   = auth()->user()->name;
            $history->status    = 'Return To Origin Hub';
            $history->note      = 'The Parcel Return To Origin Hub';
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

        Toastr::success('message', 'Return to Hub successfully!');

        return redirect()->back();

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
        $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $parcel->merchantId)->where('is_active', 1)->first();

        if ($request->status == 2) {
            $merchantinfo    = Merchant::find($parcel->merchantId);
            $deliverymanInfo = Deliveryman::where(['id' => $parcel->deliverymanId])->first();

            $url  = "http://premium.mdlsms.com/smsapi";
            $data = [
                "api_key"  => "C20005455f867568bd8c02.20968541",
                "type"     => "text",
                "contacts" => '0' . $parcel->recipientPhone,
                "senderid" => "8809612440738",
                "msg"      => "message",
                // "msg"      => "Dear $parcel->recipientName, We have received your parcel from $merchantinfo->companyName. Your Tracking ID is $parcel->trackingCode. Please click the link to track your parcel:" . url('track/parcel/') . '/' . $parcel->trackingCode . " Thanks for being with Zuri Express.",
            ];
            //   return $data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

        } elseif ($request->status == 3) {
            // $codcharge=$request->customerpay/100;
            $codcharge              = 0;
            $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
            $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
            // $parcel->codCharge = $codcharge;
            $parcel->save();

            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka   = $parcel->cod;
            $url         = "http://premium.mdlsms.com/smsapi";
            $data        = [
                "api_key"  => "C20005455f867568bd8c02.20968541",
                "type"     => "text",
                "contacts" => '0' . $parcel->recipientPhone,
                "senderid" => "8809612440738",
                // "msg"      => "Dear $parcel->recipientName \r\n your parcel is being delivered by $deliveryMan->name phone number 0$deliveryMan->phone.  Please get ready with the cash amount of $readytaka. \r\n Thanks for being with Zuri Express.",
                "msg"      => "message",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } elseif ($request->status == 4) {
            // dd($parcel);
            // 4 for delivered
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
            // $codcharge=$request->customerpay/100;
            $codcharge              = 0;
            $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
            $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
            // $parcel->codCharge = $codcharge;
            $parcel->save();

            if ($parcel->parcel_source == 'p2p') {
                $validMerchant = $parcel->getMerchantOrSenderDetails();
            } else {
                $validMerchant = Merchant::find($parcel->merchantId);
            }

            $url  = "http://premium.mdlsms.com/smsapi";
            $data = [
                "api_key"  => "C20005455f867568bd8c02.20968541",
                "type"     => "text",
                "contacts" => isset($validMerchant->phoneNumber) ? '0' . $validMerchant->phoneNumber : null,
                "senderid" => "8809612440738",
                // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode has been delivered successfully to the customer.\r\n Thanks for being with Zuri Express",
                "msg"      => "message",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } elseif ($request->status == 5) {
            // $codcharge=$request->customerpay/100;
            $codcharge              = 0;
            $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
            $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
            // $parcel->codCharge = $codcharge;
            $parcel->save();
            if ($parcel->parcel_source == 'p2p') {
                $validMerchant = $parcel->getMerchantOrSenderDetails();
            } else {
                $validMerchant = Merchant::find($parcel->merchantId);
            }
            $url  = "http://premium.mdlsms.com/smsapi";
            $data = [
                "api_key"  => "C20005455f867568bd8c02.20968541",
                "type"     => "text",
                "contacts" => '0' . $validMerchant->phoneNumber,
                "senderid" => "8809612440738",
                // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode is on hold. Another attempt will be taken the next day. \r\n Thanks for being with Zuri Express.",
                "msg"      => "message",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } elseif ($request->status == 6) {

            // 6 for partial delivery

            if ($parcel->payment_option == 2 && $parcel->parcel_source !== 'p2p') {
                $merchantinfo    = Merchant::find($parcel->merchantId);
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
                $tax       = $parcel->deliveryCharge * $charge->tax / 100;
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

            if ($parcel->parcel_source == 'p2p') {
                $validMerchant = $parcel->getMerchantOrSenderDetails();
            } else {
                $validMerchant = Merchant::find($parcel->merchantId);
            }

            $url  = "http://premium.mdlsms.com/smsapi";
            $data = [
                "api_key"  => "C20005455f867568bd8c02.20968541",
                "type"     => "text",
                "contacts" => '0' . $validMerchant->phoneNumber,
                "senderid" => "8809612440738",
                // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode will be return within 48 hours. \r\n Thanks for being with Zuri Express",
                "msg"      => "message",
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } elseif ($request->status == 8) {

            $codcharge              = 0;
            $parcel->merchantAmount = 0;
            $parcel->merchantDue    = 0;

            $parcel->save();

        } elseif ($request->status == 9) {

            // $merchantinfo =Merchant::find($parcel->merchantId);

            $codcharge = 0;

            $parcel->merchantAmount    = $codcharge;
            $parcel->merchantPaid      = $codcharge;
            $parcel->merchantDue       = $codcharge;
            $parcel->merchantpayStatus = 1;
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
        $history->done_by   = auth()->user()->name;
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
                    Mail::to([
                        $validMerchant->emailAddress,
                    ])->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                }

            } catch (\Exception $exception) {
                Log::info('Parcel status update mail error: ' . $exception->getMessage());
            }
        }

        Toastr::success('message', 'Parcel information update successfully!');

        return redirect()->back();
    }

    public function singlestatusupdate(Request $request)
    {
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

                    $deliverymanInfo = Deliveryman::where(['id' => $parcel->deliverymanId])->first();

                    $merchantinfo = Merchant::find($parcel->merchantId);

                    $history            = new History();
                    $history->name      = $parcel->recipientName;
                    $history->parcel_id = $parcel->id;
                    $history->done_by   = auth()->user()->name;
                    $history->status    = 'PICKED UP';
                    $history->note      = 'PARCEL HAS BEEN PICKED UP FROM DROP-OFF POINT';
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
                return response()->json(['success' => false, 'message' => 'No parcels updated.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid slug.']);
        }
    }
    // public function PrintSelectedItems(Request $request)
    // {

    //     $parcels = \App\Parcel::whereIn('id', $request->parcels)
    //         ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
    //         ->get();

    //     $pdf = new \Mpdf\Mpdf([
    //         'mode'              => 'utf-8',
    //         'format'            => 'A4',
    //         'orientation'       => 'P',
    //         'margin_top'        => '5',
    //         'margin_bottom'     => '5',
    //         'margin_left'       => '5',
    //         'margin_right'      => '5',
    //         'default_font_size' => '10',
    //         'default_font'      => 'sans-serif',
    //         'autoScriptToLang'  => true,
    //         'autoLangToFont'    => true,
    //         'margin_footer'     => '0',
    //         'shrink_tables'     => 1, // ✅ Fixes blank page issue for large tables

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
    //         'Content-Type'        => 'application/pdf',
    //         'Content-Disposition' => 'attachment',
    //     ]);

    // }

    public function PrintSelectedItems(Request $request)
    {
        $parcels = \App\Parcel::whereIn('id', explode(',', $request->query('parcels'))) // Accept comma-separated values
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype', 'p2pParcel'])
            ->get();
            $pdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                // 'format' => [210.0, 148.5], // Use 148.5 for exact half of A4 height
                'format' => 'A4', // Use 148.5 for exact half of A4 height
                'orientation' => 'P',
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
                'default_font_size' => 10,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'margin_footer' => 0,
                'shrink_tables' => 1
            ]);

        $view = 'pdf.pdf';
        $html = trim(view($view, ['parcels' => $parcels])->render());
        $pdf->WriteHTML($html);

        $timestamp = Carbon::now()->format('j-M-Y g:i A'); // 12-hour format with AM/PM
        $filename = "ZiDrop_Waybill_{$timestamp}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S'); 
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment',
        ]);
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
            $activeSubPlan = MerchantSubscriptions::with('plan')->where('merchant_id', $parcel->merchantId)->where('is_active', 1)->first();


            if ($request->note) {
                $note           = new Parcelnote();
                $note->parcelId = $id;
                $note->note     = $request->note;
                $note->save();
            }

            if ($request->status == 2) {
                $deliverymanInfo = Deliveryman::where(['id' => $parcel->deliverymanId])->first();
                $merchantinfo    = Merchant::find($parcel->merchantId);

                $url  = "http://premium.mdlsms.com/smsapi";
                $data = [
                    "api_key"  => "C20005455f867568bd8c02.20968541",
                    "type"     => "text",
                    "contacts" => '0' . $parcel->recipientPhone,
                    "senderid" => "8809612440738",
                    "msg"      => "message",
                    // "msg"      => "Dear $parcel->recipientName, We have received your parcel from $merchantinfo->companyName. Your Tracking ID is $parcel->trackingCode. Please click the link to track your parcel:" . url('track/parcel/') . '/' . $parcel->trackingCode . " Thanks for being with Zuri Express.",
                ];
                //   return $data;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);

            } elseif ($request->status == 3) {
                // $codcharge=$request->customerpay/100;
                $codcharge              = 0;
                $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
                $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
                // $parcel->codCharge = $codcharge;
                $parcel->save();

                $validMerchant = Merchant::find($parcel->merchantId);
                $deliveryMan   = Deliveryman::find($parcel->deliverymanId);
                $readytaka     = $parcel->cod;
                $url           = "http://premium.mdlsms.com/smsapi";
                $data          = [
                    "api_key"  => "C20005455f867568bd8c02.20968541",
                    "type"     => "text",
                    "contacts" => '0' . $parcel->recipientPhone,
                    "senderid" => "8809612440738",
                    // "msg"      => "Dear $parcel->recipientName \r\n your parcel is being delivered by $deliveryMan->name phone number 0$deliveryMan->phone.  Please get ready with the cash amount of $readytaka. \r\n Thanks for being with Zuri Express.",
                    "msg"      => "message",
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
            } elseif ($request->status == 4) {
                // $codcharge=$request->customerpay/100;
                $codcharge              = 0;
                $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
                $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
                // $parcel->codCharge = $codcharge;
                $parcel->save();

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

                if ($parcel->parcel_source == 'p2p') {
                    $validMerchant = $parcel->getMerchantOrSenderDetails();
                } else {
                    $validMerchant = Merchant::find($parcel->merchantId);
                }
                $url  = "http://premium.mdlsms.com/smsapi";
                $data = [
                    "api_key"  => "C20005455f867568bd8c02.20968541",
                    "type"     => "text",
                    "contacts" => '0' . $validMerchant->phoneNumber,
                    "senderid" => "8809612440738",
                    // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode has been delivered successfully to the customer.\r\n Thanks for being with Zuri Express",
                    "msg"      => "message",
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
            } elseif ($request->status == 5) {
                // $codcharge=$request->customerpay/100;
                $codcharge              = 0;
                $parcel->merchantAmount = ($parcel->merchantAmount) - ($codcharge);
                $parcel->merchantDue    = ($parcel->merchantAmount) - ($codcharge);
                // $parcel->codCharge = $codcharge;
                $parcel->save();

                if ($parcel->parcel_source == 'p2p') {
                    $validMerchant = $parcel->getMerchantOrSenderDetails();
                } else {
                    $validMerchant = Merchant::find($parcel->merchantId);
                }
                $url  = "http://premium.mdlsms.com/smsapi";
                $data = [
                    "api_key"  => "C20005455f867568bd8c02.20968541",
                    "type"     => "text",
                    "contacts" => '0' . $validMerchant->phoneNumber,
                    "senderid" => "8809612440738",
                    // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode is on hold. Another attempt will be taken the next day. \r\n Thanks for being with Zuri Express.",
                    "msg"      => "message",
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
            } elseif ($request->status == 6) {

                if ($parcel->payment_option == 2 && $parcel->parcel_source !== 'p2p') {
                    $merchantinfo    = Merchant::find($parcel->merchantId);

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
                    $tax       = $parcel->deliveryCharge * $charge->tax / 100;
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

                if ($parcel->parcel_source == 'p2p') {
                    $validMerchant = $parcel->getMerchantOrSenderDetails();
                } else {
                    $validMerchant = Merchant::find($parcel->merchantId);
                }
                $url  = "http://premium.mdlsms.com/smsapi";
                $data = [
                    "api_key"  => "C20005455f867568bd8c02.20968541",
                    "type"     => "text",
                    "contacts" => '0' . $validMerchant->phoneNumber,
                    "senderid" => "8809612440738",
                    // "msg"      => "Dear $validMerchant->firstName, Your Parcel ID $parcel->trackingCode will be return within 48 hours. \r\n Thanks for being with Zuri Express",
                    "msg"      => "message",
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
            } elseif ($request->status == 8) {

                $codcharge              = 0;
                $parcel->merchantAmount = 0;
                $parcel->merchantDue    = 0;
                $parcel->save();

            } elseif ($request->status == 9) {

                // $merchantinfo =Merchant::find($parcel->merchantId);

                $codcharge = 0;

                // $parcel->merchantAmount = $parcel->merchantAmount + $parcel->codCharge;
                // $parcel->merchantPaid = $parcel->merchantAmount + $parcel->codCharge;
                $parcel->merchantAmount    = $codcharge;
                $parcel->merchantPaid      = $codcharge;
                $parcel->merchantDue       = $codcharge;
                $parcel->merchantpayStatus = 1;
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
            $history->done_by   = auth()->user()->name;
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
                        Mail::to([
                            $validMerchant->emailAddress,
                        ])->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                    }

                } catch (\Exception $exception) {
                    Log::info('Parcel status update mail error: ' . $exception->getMessage());
                }
            }

        }

        Toastr::success('message', 'Parcel information update successfully!');

        return redirect()->back();
    }

    public function create()
    {
        $merchants = Merchant::with('activeSubscription')->orderBy('id', 'DESC')->get();
        $delivery  = Deliverycharge::where('status', 1)->get();
        // $packages = Deliverycharge::where('status', 1)->get();

        return view('backEnd.addparcel.create_new', compact('merchants', 'delivery'));
    }

    public function parcelstore(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'percelType'     => 'required',
            'name'           => 'required',
            'order_number'           => 'required',
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

            $insurance = ($codAmt) * $charge->insurance / 100;
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
        // $store_parcel->vehicle_type = $request->vehicle_type;
        // $store_parcel->pickup_or_drop_option = $request->pickupOrdropOff;
        // $store_parcel->pickup_or_drop_location = $request->addressofSender;
        $store_parcel->save();

        if ($request->payment_option == 1) {
            RemainTopup::create([
                'parcel_id'     => $store_parcel->id,
                'parcel_status' => 1,
                'merchant_id'   => $store_parcel->merchantId,
                'amount'        => $deliverycharge + $tax + $insurance,
            ]);
        }

        $history            = new History();
        $history->name      = "Customer: " . $store_parcel->recipientName . "<br><b>(Created By: )</b>" . auth()->user()->name;
        $history->parcel_id = $store_parcel->id;
        $history->done_by   = auth()->user()->name;
        $history->status    = 'Parcel Created By ' . auth()->user()->name;
        $history->note      = $request->note ? $request->note : 'Pending Pickup';
        $history->date      = $store_parcel->updated_at;
        $history->save();

        $note           = new Parcelnote();
        $note->parcelId = $store_parcel->id;
        $note->note     = 'Pending Pickup';
        $note->save();

        Toastr::success('Success!', 'Thanks! your parcel add successfully');
        session()->flash('open_url', url('/editor/parcel/invoice/' . $store_parcel->id));

        return redirect()->back();
    }

    public function disablesubsplan($id)
    {    
        $subs = MerchantSubscriptions::where('id', $id)
            ->where('is_active', 1)
            ->first();
    
        if (!$subs) {
            return redirect()->back()->with('error', 'Subscription not Activate.');
        }
    
        $subs->is_active = 0;
        $subs->auto_renew = 0;
        $subs->disable_by = 'admin';
        $subs->disable_by_id = auth()->user()->id;
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

    public function parceledit($id)
    {
        $edit_data = Parcel::where('id', $id)
            ->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')
            ->first();
        $merchants = Merchant::with('activeSubscription')->orderBy('id', 'DESC')->get();
        $delivery  = Deliverycharge::where('status', 1)->get();
        $delTowns = \App\Town::where('cities_id', $edit_data->delivery_cities_id)->get();

        return view('backEnd.addparcel.edit_new', compact('edit_data', 'merchants', 'delivery', 'delTowns'));
    }

    public function parcelupdate(Request $request)
    {

        $update_parcel = Parcel::find($request->hidden_id);
        if ($update_parcel->parcel_source == 'p2p') {
            $this->validate($request, [
                'percelType'   => 'required',
                'name'         => 'required',
                'address'      => 'required',
                'phonenumber'  => 'required',
                'productName'  => 'required',
                'productQty'   => 'required',
                'weight'       => 'required',
                'note'         => 'required',
                'pickuptown'   => 'required',
                'pickupcity'   => 'required',
                'deliverycity' => 'required',
                'deliverytown' => 'required',
            ]);

        } else {

            $this->validate($request, [
                'percelType'     => 'required',
                'name'           => 'required',
                'order_number'           => 'required',
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

        }
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
        if ($update_parcel->parcel_source == 'p2p') {

            $insurance = ($packageAmt) * $charge->insurance / 100;
            // if ($merchant->ins_cal_permission == 0) {
            //     $insurance = 0; // Override if permission is disabled
            // }
            $insurance      = round($insurance, 2);
            $totalDelCharge = $deliverycharge + $tax + $insurance;
            $codcharge      = 0;

        } else {
            if ($request->payment_option == 2) {
                // 2 for pay on delivery
                $insurance = ($codAmt) * $charge->insurance / 100;
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

                $insurance = ($packageAmt) * $charge->insurance / 100;
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
        }

        $update_parcel->invoiceNo = $request->invoiceno;
        if ($update_parcel->parcel_source !== 'p2p') {
            $update_parcel->merchantId = $request->merchantId;
        }
        $update_parcel->cod              = $codAmt;
        $update_parcel->package_value    = $packageAmt;
        $update_parcel->tax              = $tax;
        $update_parcel->percelType       = $request->percelType;
        $update_parcel->order_number    = $request->order_number;
        $update_parcel->recipientName    = $request->name;
        $update_parcel->recipientAddress = $request->address;
        $update_parcel->recipientPhone   = $request->phonenumber;
        $update_parcel->productName      = $request->productName;
        $update_parcel->productQty       = $request->productQty;
        $update_parcel->productColor     = $request->productColor;
        $update_parcel->productWeight    = $request->weight;
        $update_parcel->reciveZone       = $request->reciveZone;
        $update_parcel->note             = $request->note ?? '';
        $update_parcel->deliveryCharge   = $deliverycharge;
        if ($update_parcel->parcel_source !== 'p2p') {
            $update_parcel->codCharge      = $merchant->cod_cal_permission == 0 ? 0 : $codcharge;
            $update_parcel->merchantAmount = $merchantAmount;
            $update_parcel->merchantDue    = $merchantDue;
            $update_parcel->insurance      = $merchant->ins_cal_permission == 0 ? 0 : $insurance;
        }
        $update_parcel->orderType          = $request->orderType;
        $update_parcel->pickup_cities_id   = $request->pickupcity;
        $update_parcel->delivery_cities_id = $request->deliverycity;
        $update_parcel->pickup_town_id     = $request->pickuptown;
        $update_parcel->delivery_town_id   = $request->deliverytown;
        if ($update_parcel->agentId) {
            $update_parcel->agentAmount = $codAmt;
        }
        $update_parcel->save();

        if ($update_parcel->parcel_source !== 'p2p') {
            if ($request->payment_option == 1) {
                $RemainTopup = RemainTopup::where('parcel_id', $request->hidden_id)->first();
                if ($RemainTopup) {
                    $RemainTopup->amount      = $deliverycharge + $tax + $insurance;
                    $RemainTopup->merchant_id = $request->merchantId;
                    $RemainTopup->save();
                } else {
                    RemainTopup::create([
                        'parcel_id'     => $update_parcel->id,
                        'parcel_status' => 1,
                        'merchant_id'   => $update_parcel->merchantId,
                        'amount'        => $deliverycharge + $tax + $insurance,
                    ]);
                }

            }
        }

        //Save to History table
        $parcel = Parcel::find($request->hidden_id);

        $history            = new History();
        $history->name      = $parcel->recipientName;
        $history->parcel_id = $request->hidden_id;
        $history->done_by   = auth()->user()->name;
        $history->status    = 'Parcel Edited By ' . auth()->user()->name;
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

    public function merchantInvoice($id)
    {
        // return $id;
        $parceltype = Parceltype::where('slug', 'return-to-merchant')->first();
        $marchent   = Merchant::find($id);
        $parcels    = Parcel::where('merchantId', $id)->where('status', $parceltype->id)->where('pay_return', 0)->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->get();
        $update     = $parcels->first()->updated_at;
        // dd($marchent->toArray(), $parcels->toArray());

        return view('backEnd.parcel.invoice_return_to_merchat', compact('parcels', 'marchent', 'update'));
    }

    public function get_parcel_by_qr(Request $request, $trackingCode)
    {
        $OLDTrackingCodesQRscans = session()->get('OLDTrackingCodesQRscans');
        $sessionBeepSound        = session()->get('beepSound');
        $beepSound               = $sessionBeepSound ?? false;
        $beepSoundPass           = $beepSound;

        if (! empty($OLDTrackingCodesQRscans)) {

            if (! in_array($trackingCode, $OLDTrackingCodesQRscans)) {
                $OLDTrackingCodesQRscans[] = $trackingCode;
                session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
                $beepSound = true;
            }

        } else {
            $OLDTrackingCodesQRscans[] = $trackingCode;
            session()->put('OLDTrackingCodesQRscans', $OLDTrackingCodesQRscans);
            $beepSound = true;
        }

        // return $OLDTrackingCodesQRscans;

        $slug = $request->input('slug');
        if ($slug === 'all') {
            $parceltype = '';
        } else {
            $parceltype = Parceltype::where('slug', $slug)->first();
        }

        if ($request->slug == 'return-to-merchant') {
            $canEdit = false;
        } else {
            $canEdit = true;
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

        $length = 10;

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

        $html .= '<thead>
            <tr>
                <th> <input type="checkbox" id="My-Buttonn"></th>
                <th>Tracking</th>
                <th>Action</th>
                <th>Merchant Id</th>
                <th>Create_Date</th>
                <th>Company_Name</th>
                <th>Customer</th>
                <th width="10%">City/Town</th>
                <th>Full Address</th>
                <th>Phone</th>
                <th>Pickman</th>
                <th>Rider</th>
                <th>Agent</th>
                <th>Last Update</th>
                <th>Status</th>
                <th>Total</th>
                <th>Declared Value</th>
                <th>Charge</th>
                <th>Cod Charge</th>
                <th>Tax</th>
                <th>Insurance</th>
                <th>Sub Total</th>
                <th>Pay Return ?</th>
                <th>Pay ?</th>
            </tr>
        </thead>';

        foreach ($show_data as $key => $value) {
            $parcelstatus = Parceltype::find($value->status);
            // New data
            // $merchant = Merchant::find($value->merchantId);
            // $agentInfo = Agent::find($value->agentId);
            $deliverymanInfo = Deliveryman::find($value->deliverymanId);
            $pickupmanInfo   = Deliveryman::find($value->pickupmanId);
            $merchantDetails = $value->getMerchantOrSenderDetails();

            if ($parceltype && $parceltype->title !== $parcelstatus->title) {
                continue;
            }
            $total += 1;

            $html .= ' <tbody><tr class="data_all_trs">';
            $html .= '<td><input type="checkbox" class="selectItemCheckbox" checked value="' . $value->id . '" data-status="' . $parcelstatus->id . '" name="parcel_id[]" form="myform"></form></td>';

            // $html .= '<td><input type="checkbox" class="selectItemCheckbox" value="' . $value->id . '" name="parcel_id[]" form="myform" checked> </form></td>';

            $html .= '<td class="trackingCode">' . $value->trackingCode . '</td>';
            $html .= '<td><ul class="action_buttons cust-action-btn">';

            if (Auth::user()->role_id <= 2) {

                if ($canEdit) {
                    $html .= '<li  class="m-1"><a href="' . url('editor/parcel/edit/' . $value->id) . '" class="edit_icon"><i class="fa fa-edit"></i></a></li>';
                }

                $html .= '<li class="m-1"><a class="edit_icon anchor" target="_blank" href="' . url('editor/parcel/invoice/' . $value->id) . '" title="Invoice"><i class="fa fa-list"></i></a></li>';
            }

            if (Auth::user()->role_id <= 3 && $canEdit) {
                $html .= '<li class="m-1"><button class="thumbs_up" title="Action" id="sUpdateModal" data-id="' . $value->id . '" data-recipientPhone="' . $value->recipientPhone . '" data-parcel_status_update="' . $value->status . '"><i class="fa fa-pencil"></i></button></li>';
            }

            $html .= '<li class="m-1">';

            if (Auth::user()->role_id <= 2) {
                $html .= '<button class="edit_icon" href="#" id="merchantParcelh" data-id="' . $value->id . '" title="History"><i class="fas fa-history"></i></button>';
            }

            $html .= '</li>';
            // if($value->parcel_source == 'p2p'){
            //     $html .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' .$value->p2pParcel->sender_name  .  '" data-type="'. 'p2p' .'" data-phonenumber = "' . $value->p2pParcel->sender_mobile . '" data-emailaddress = "' . $value->p2pParcel->sender_email . '" data-companyname = "' . 'P2P' . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename .   '" data-pickup = "' . $value->pickupcity->title. '/'. $value->pickuptown->title .'" data-delivery = "' . $value->deliverycity->title. '/'. $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '"data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '" ><i class="fa fa-eye"></i></button></li>';
            // }else{
            $html .= '<li class="m-1"><button class="edit_icon" href="#" id="merchantParcel" title="View" data-firstname = "' . $merchantDetails->firstName . '" data-order_number = "' . $value->order_number . '" data-lastname = "' . $merchantDetails->lastName . '" data-phonenumber = "' . $merchantDetails->phoneNumber . '" data-emailaddress = "' . $merchantDetails->emailAddress . '" data-companyname = "' . $merchantDetails->companyName . '" data-recipientname = "' . $value->recipientName . '" data-recipientaddress = "' . $value->recipientAddress . '" data-zonename = "' . $value->zonename . '" data-pickup = "' . $value->pickupcity->title . '/' . $value->pickuptown->title . '" data-delivery = "' . $value->deliverycity->title . '/' . $value->deliverytown->title . '" data-title = "' . $value->title . '" data-cod = "' . number_format($value->cod, 2) . '"data-package_value = "' . number_format($value->package_value, 2) . '" data-codcharge = "' . number_format($value->codCharge, 2) . '" data-deliverycharge = "' . number_format($value->deliveryCharge, 2) . '" data-merchantamount = "' . number_format($value->merchantAmount, 2) . '" data-merchantpaid = "' . number_format($value->merchantPaid, 2) . '" data-merchantdue = "' . number_format($value->merchantDue, 2) . '" data-created_at = "' . $value->created_at . '" data-updated_at = "' . $value->updated_at . '" data-tax = "' . number_format($value->tax, 2) . '" data-insurance = "' . number_format($value->insurance, 2) . '" ><i class="fa fa-eye"></i></button></li>';
            // }

            $html .= '</ul></td>';
            $html .= '<td>' . (isset($value->merchant) ? $value->merchant->id : 'P2P') . '</td>';

            $html .= '<td>' . date('d M Y', strtotime($value->created_at)) . '<br>' . date("g:i a", strtotime($value->created_at)) . '</td>';
            $html .= '<td>' . $merchantDetails->companyName . '<br>(' . $merchantDetails->pickLocation . ')<br>(' . $merchantDetails->phoneNumber . ')</td>';
            $html .= '<td>' . $value->recipientName . '</td>';
            $html .= '<td width="10%">' . $value->deliverycity->title . '/' . $value->deliverytown->title . '</td>';
            $html .= '<td>' . $value->recipientAddress . '</td>';
            $html .= '<td>' . $value->recipientPhone . '</td>';

            if ($value->pickupmanId) {
                $html .= '<td><button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '">' . $pickupmanInfo->name . '</button></td>';
            } else {
                $html .= '<td><button class="btn btn-primary" id="pickupmanModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '">Asign</button></td>';
            }

            if ($value->deliverymanId) {
                $html .= '<td><button class="btn btn-info" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-deliverymanidselectvalue="' . $value->deliverymanId . '">' . $deliverymanInfo->name . '</button></td>';
            } else {
                $html .= '<td><button class="btn btn-primary" id="asignModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-deliverymanidselectvalue="0">Asign</button></td>';
            }

            if ($value->agentId) {

                $html .= '<td><button class="btn btn-success" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-agentis_selecetvalue="' . $value->agentId . '">' . $value->agent->name . '</button></td>';
            } else {
                $html .= '<td><button class="btn btn-primary" id="agentModal" data-id="' . $value->id . '" data-merchant_phone="' . $merchantDetails->phoneNumber . '" data-agentis_selecetvalue="0">Asign</button></td>';
            }

            $html .= '<td>' . date('d M Y', strtotime($value->updated_at)) . '<br>' . date("g:i a", strtotime($value->updated_at)) . '</td>';
            $html .= '<td>' . $value->parceltype->title . '</td>';
            $html .= '<td>' . number_format($value->cod, 2) . '</td>';
            $html .= '<td>' . number_format($value->package_value, 2) . '</td>';
            $html .= '<td>' . number_format($value->deliveryCharge, 2) . '</td>';
            $html .= '<td>' . number_format($value->codCharge, 2) . '</td>';
            $html .= '<td>' . number_format($value->tax, 2) . '</td>';
            $html .= '<td>' . number_format($value->insurance, 2) . '</td>';
            $html .= '<td>' . number_format($value->cod - ($value->deliveryCharge + $value->codCharge + $value->tax + $value->insurance), 2) . '</td>';

            if ($value->pay_return == 0) {
                $html .= '<td> <div class="text-danger">' . number_format($value->deliveryCharge, 2) . '</div></td>';
            } else {
                $html .= '<td> <div class="text-success"> Paid </div></td>';
            }
            if ($value->merchantpayStatus == null) {
                $html .= '<td> <div class="text-danger"> NULL </div></td>';
            } elseif ($value->merchantpayStatus == 0) {
                $html .= '<td> <div class="text-danger"> Processing </div></td>';
            } else {
                $html .= '<td> <div class="text-success"> Paid </div></td>';
            }

            $html .= '</tr> </tbody>';

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

}
