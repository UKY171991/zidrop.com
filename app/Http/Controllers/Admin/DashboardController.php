<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Parcel;
use App\Merchant;
use App\Merchantpayment;
class DashboardController extends Controller
{
    public function index(){
    
    	return view('backEnd.superadmin.dashboard');
    }
    
    public function bulkpayment(Request $request){
        $selectption = $request->selectptions;
        if($selectption==1){
        	$payment = new Merchantpayment();
        	$payment->merchantId = $request->merchantId;
        	$payment->parcelId   = $request->parcelId;
        	$payment->done_by   = auth()->user()->name;
        	$payment->save();
            $parcels_id = $request->parcel_id;
            $total = 0;
            foreach($parcels_id as $parcel_id){
                $parcel         =   Parcel::find($parcel_id);
          
              
		    	
                $parcel->paymentInvoice = $payment->id;
                $parcel->merchantPaid = $parcel->merchantAmount;
		    	$parcel->merchantDue = 0;
		    	$parcel->merchantpayStatus = 1;
		    	$parcel->timestamps = false;
		      
		        
		        
		    	$parcel->save();
		    	$parcel->timestamps = true;
		    	$parcel->save();
		    	
		    	$total +=$parcel->cod-($parcel->deliveryCharge+$parcel->codCharge);
		    	
            }
         $totalparcel = count(collect($request)->get('parcel_id'));
         $validMerchant = Merchant::find($request->merchantId);
            
          $url = "http://premium.mdlsms.com/smsapi";
          $data = [
            "api_key" => "C20005455f867568bd8c02.20968541",
            "type" => "text",
            "contacts" => "0$validMerchant->phoneNumber",
            "senderid" => "8809612440738",
            "msg" => "A Payment (Invoice No. $payment->id) has been issued of $total Tk where $totalparcel Parcels were processed. Check Invoice on your dashboard.\r\n Thanks for being with Aschi",
          ];
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $response = curl_exec($ch);
          curl_close($ch);
            Toastr::success('message', 'Invoice Processing successfully!');
            return redirect()->back();
        }elseif($selectption==0){
            $parcels_id = $request->parcel_id;
            foreach($parcels_id as $parcel_id){
                $parcel         =   Parcel::find($parcel_id);
              
               
                $oldcreate_date = $parcel->created_at;
              
		    	$parcel->updated_at = $oldcreate_date;
                $parcel->merchantpayStatus = 0;
		    	$parcel->save();
        }
        
         Toastr::success('message', 'Invoice Paid successfully!');
         return redirect()->back();
		}
	}
}
