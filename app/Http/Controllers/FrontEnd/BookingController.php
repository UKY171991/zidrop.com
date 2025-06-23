<?php

namespace App\Http\Controllers\FrontEnd;

use App\Deliverycharge;
use App\History;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\Booking;
use App\Parcelnote;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Session;

class BookingController extends Controller
{
    public  function index(){
        $delivery = Deliverycharge::where('status', 1)->get();

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
        return view('frontEnd.layouts.pages.booking',compact('delivery'));
    }
    public function parcelstore(Request $request)
    {

        //print_r($_POST); die;
        $this->validate($request, [
            'percelType'    => 'required',  // Type of parcel
            'name'          => 'required',  // Sender's name
            'pickupcity'    => 'required',  // Pickup city
            'pickuptown'    => 'required',  // Pickup town
            'phonenumber'   => 'required',  // Sender's phone number
            'address'       => 'required',  // Sender's address
            'note'          => 'required',  // Sender's note
            'r_name'        => 'required',  // Receiver's name
            'deliverycity'  => 'required',  // Delivery city
            'deliverytown'  => 'required',  // Delivery town
            'r_phonenumber' => 'required',  // Receiver's phone number
            'r_address'     => 'required',  // Receiver's address
            'r_note'        => 'required',  // Receiver's note
            'productName'   => 'required',  // Product name
            'productQty'    => 'required',  // Product quantity
            'cod'           => 'required',  // Cash on delivery amount
            'weight'        => 'required',  // Parcel weight
            'package_value' => 'required',  // Value of the package
            'productColor'  => 'required',  // Color of the product
        ]);
        
               
        // dd($request->all());   
        $merchant = Merchant::find(Session::get('merchantId'));     
        $charge = \App\ChargeTarif::where('pickup_cities_id', $request->pickupcity)->where('delivery_cities_id', $request->deliverycity)->first();
        $town = \App\Town::where('id', $request->deliverytown)->where('cities_id', $request->deliverycity)->first();

        if ($request->weight > 1 || $request->weight != null) {
            $extraweight = $request->weight - 1;
            $deliverycharge = ($charge->deliverycharge + $town->towncharge) + ($extraweight * $charge->extradeliverycharge);
            $weight = $request->weight;
        } else {
            $deliverycharge = $charge->deliverycharge + $town->towncharge;
            $weight = 1;
        }

       // Tax Calculation And Insurance calculation
       $tax = $deliverycharge * $charge->tax / 100;
       $tax = round($tax, 2);

       
        if ($request->payment_option == 2) {
            // 2 for cash on delivery
            // $state = Deliverycharge::find($request->package);
            
            $insurance = $request->cod ? $request->cod * $charge->insurance / 100 : 0;
            $insurance = round($insurance, 2);
             
            if ($charge) {
                $codcharge = ($request->cod * $charge->codcharge) / 100;
            } else {
                $codcharge = 0;
            }

            $merchantAmount = ($request->cod) - ($deliverycharge + $codcharge + $tax + $insurance);
            $merchantDue = ($request->cod) - ($deliverycharge + $codcharge + $tax + $insurance);


        } else {
            $insurance = $request->package_value ? $request->package_value * $charge->insurance / 100 : 0;
            $insurance = round($insurance, 2);

             $merchant = Merchant::find(Session::get('merchantId'));
            $totalDelCharge = $deliverycharge + $tax + $insurance;

            if ($merchant->balance < $totalDelCharge) {
                session()->flash('message', 'Wallet Balance is low. Please
                top up.');

                return redirect()->back();
            }

            $merchant->balance = $merchant->balance - $totalDelCharge;
            $merchant->save();
            $codcharge = 0;
            $merchantAmount = 0;
            $merchantDue = 0;
        }

        $store_parcel = new Booking();
        $store_parcel->sender_name = $request->sender_name;  // Sender's name from the form
        $store_parcel->sender_address = $request->sender_address;  // Sender's address
        $store_parcel->sender_city = $request->pickupcity;  // Pickup city
        $store_parcel->sender_state = $request->sender_state ?? '';  // Optional, since not in the form
        $store_parcel->sender_pincode = $request->sender_pincode ?? '';  // Optional
        $store_parcel->sender_phone = $request->phonenumber;  // Sender's phone number
        $store_parcel->receiver_name = $request->r_name;  // Receiver's name
        $store_parcel->receiver_address = $request->r_address;  // Receiver's address
        $store_parcel->receiver_city = $request->deliverycity;  // Delivery city
        $store_parcel->receiver_state = $request->receiver_state ?? '';  // Optional, since not in the form
        $store_parcel->receiver_pincode = $request->receiver_pincode ?? '';  // Optional
        $store_parcel->receiver_phone = $request->r_phonenumber;  // Receiver's phone number
        $store_parcel->parcel_type = $request->percelType;  // Parcel type (regular, liquid, fragile)
        $store_parcel->parcel_weight = $request->weight;  // Parcel weight
        $store_parcel->parcel_value = $request->package_value ?? 0;  // Value of the parcel
        $store_parcel->parcel_quantity = $request->productQty;  // Quantity of parcels
        $store_parcel->parcel_color = $request->productColor;  // Color of the parcel
        $store_parcel->cod_amount = $request->cod ?? 0;  // Cash on Delivery amount
        $store_parcel->note = $request->note ?? '';  // Additional note
        $store_parcel->tracking_code = 'ZD' . mt_rand(1111111111, 9999999999);  // Random tracking code
        $store_parcel->created_at = now();  // Set current timestamp for creation
        $store_parcel->updated_at = now();  // Set current timestamp for update
        $store_parcel->save();


        if ($request->payment_option == 1) {
            RemainTopup::create([
                'parcel_id' => $store_parcel->id,
                'parcel_status' => 1,
                'merchant_id' => $merchant->id,
                'amount' => $deliverycharge + $tax + $insurance,
            ]);
        }
        $history = new History();
        $history->name = "Customer: " . $store_parcel->recipientName . "<br><b>(Created By:  )</b>" .  $merchant->companyName;
        $history->parcel_id = $store_parcel->id;
        $history->done_by = $merchant->companyName;
        $history->status = 'Parcel Created By ' . $merchant->companyName;
        $history->note = $request->note ? $request->note : 'Pending Pickup';
        $history->date = $store_parcel->updated_at;
        $history->save();

        $note = new Parcelnote();
        $note->parcelId = $store_parcel->id;
        $note->note =  'Pending Pickup' ;
        $note->save();

       
        Toastr::success('Success!', 'Thanks! your parcel add successfully');

        session()->flash('open_url', url('/merchant/parcel/invoice/' . $store_parcel->id));
        return redirect()->back();
    }

    
}
