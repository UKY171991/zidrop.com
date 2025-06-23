<?php

namespace App\Http\Controllers\Api;

use App\Deliveryman;
use App\History;
use App\Http\Controllers\Controller;
use App\Mail\ParcelStatusUpdateEmail;
use App\Merchant;
use App\Note;
use App\Parcel;
use App\Parcelnote;
use App\Parceltype;
use App\Pickup;
use App\Deliverycharge;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;

class ApiDeliveryman extends Controller {

    public function login(Request $request) {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);
        $checkAuth = Deliveryman::where('email', $request->email)->first();

        if ($checkAuth) {

            if ($checkAuth->status == 0) {
                return ["success" => false, "message" => "Opps! your account has been suspends", "data" => null];
            } else {
                // if(password_verify($request->password,$checkAuth->password)){
                return ["success" => true, "message" => "Thanks, You are login successfully", "data" => $checkAuth];

// }else{

//   return ["success"=>false, "message"=>"Sorry! your password wrong", "data"=>null];
                // }
            }

        } else {
            return ["success" => false, "message" => "Opps! you have no account", "data" => null];
        }

    }

    public function passwordReset(Request $request) {
        $this->validate($request, [
            'email' => 'required',
        ]);
        $validDeliveryman = Deliveryman::Where('email', $request->email)->first();

        if ($validDeliveryman) {
            $verifyToken                     = rand(111111, 999999);
            $validDeliveryman->passwordReset = $verifyToken;
            $validDeliveryman->save();

            $data = [
                'contact_mail' => $validDeliveryman->email,
                'verifyToken'  => $verifyToken,
            ];

            $send = Mail::send('frontEnd.layouts.pages.deliveryman.forgetemail', $data, function ($textmsg) use ($data) {
                $textmsg->from('support@zuri.express');
                $textmsg->to($data['contact_mail']);
                $textmsg->subject('Forget password token');
            });

            return ["success" => true, "message" => "Verification code sent into your email", "resetDeliverymanId" => $validDeliveryman->id];
        } else {
            return ["success" => false, "message" => "Sorry! You have no account", "resetDeliverymanId" => null];
        }

    }

    public function verifyAndChangePassword(Request $request) {
        $id = $request->header('id');

        $validDeliveryman = Deliveryman::find($id);

        if ($validDeliveryman->passwordReset == $request->verifyPin) {
            $validDeliveryman->password      = bcrypt(request('newPassword'));
            $validDeliveryman->passwordReset = NULL;
            $validDeliveryman->save();

            return ["success" => true, "message" => "Wow! Your password reset successfully"];
        } else {
            return ["success" => false, "message" => "Sorry! Your process something wrong"];
        }

    }

    public function dashboard(Request $request) {
        $id = $request->header('id');

        $totalparcel  = Parcel::where(['deliverymanId' => $id])->count();
        $totalpickup  = Parcel::where(['pickupmanId' => $id])->count();
        $totalPending = Parcel::where([['deliverymanId', '=', $id], ['status', '=', 1]])
            ->orWhere([['pickupmanId', '=', $id], ['status', '=', 1]])->count();
        $totalInTransit = Parcel::where(['deliverymanId' => $id, 'status' => 3])->count();
        $totaldelivery  = Parcel::where(['deliverymanId' => $id, 'status' => 4])->count();
        $totalhold      = Parcel::where(['deliverymanId' => $id, 'status' => 5])->count();
        $totalcancel    = Parcel::where(['deliverymanId' => $id, 'status' => 9])->count();
        $returnpendin   = Parcel::where(['deliverymanId' => $id, 'status' => 6])->count();

        $pickup = Pickup::where('deliveryman', $id)->whereNotIn('status', [1])->count();

        $totalamount  = Parcel::where(['deliverymanId' => $id, 'status' => 4])->sum('deliverymanAmount');
        $unpaidamount = Parcel::where(['deliverymanId' => $id, 'deliverymanPaystatus' => 0])->sum('deliverymanAmount');
        $paidamount   = Parcel::where(['deliverymanId' => $id, 'deliverymanPaystatus' => 1])->sum('deliverymanAmount');

        return ['totalParcel' => (int) $totalparcel, 'totalPickup' => (int) $totalpickup, 'totalPending' => (int) $totalPending, 'totalInTransit' => (int) $totalInTransit, 'totalDelivery' => (int) $totaldelivery, 'totalHold' => (int) $totalhold, 'totalCancel' => (int) $totalcancel, 'returnPending' => (int) $returnpendin, 'totalAmount' => (int) $totalamount, 'unpaidAmount' => (int) $unpaidamount, 'paidAmount' => (int) $paidamount, 'pickup' => (int) $pickup];
    }

    public function parcels(Request $request, $startFrom) {
        $id   = $request->header('id');
        $type = (int) $request->header('type');

//   $allparcel = DB::table('parcels')

//   ->join('merchants', 'merchants.id','=','parcels.merchantId')

//   ->where('parcels.deliverymanId',$id)

//   ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')

//   ->orderBy('id','DESC')

//   ->skip($startFrom)

//   ->take(20)

//   ->get();

        if ($type == 0) {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where('parcels.deliverymanId', $id)
                ->orWhere('parcels.pickupmanId', $id)
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('id', 'DESC')

// ->skip($startFrom)
            // ->take(20)
                ->get();
        } else {
            $allparcel = DB::table('parcels')
                ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->where([['parcels.deliverymanId', '=', $id], ['parcels.status', '=', $type]])
                ->orWhere([['parcels.pickupmanId', '=', $id], ['parcels.status', '=', $type]])
                ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
                ->orderBy('id', 'DESC')

// ->skip($startFrom)
            // ->take(20)
                ->get();
        }

        return $allparcel;
    }

    function getNotes() {
        $notes = Note::where('status', 1)->get();

        return $notes;
    }

    public function parcel(Request $request, $parcelId) {
        $id = $request->header('id');

        $allparcel = DB::table('parcels')
            ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
            ->join('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
            ->join('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
        // ->where('parcels.deliverymanId',$id)
            ->where('parcels.id', $parcelId)
            ->select('parcels.*', 'merchants.companyName', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'deliverycharges.title', 'nearestzones.zonename')
            ->orderBy('id', 'DESC')
            ->get();

        return $allparcel;
    }

    public function parcelStatusUpdate(Request $request) {
        $this->validate($request, [
            'status' => 'required',
        ]);
        /*Log::info("Header Data");
        Log::info(request()->headers);
        Log::info("Request Data");
        Log::info(request()->all());*/

        $parcel = Parcel::find($request->hidden_id);

        // if ($parcel->status == 4) {
        //     return ["success" => false, "message" => "You can't update parcel information"];
        // } else {
            $parcel->status = $request->status;
            $parcel->updated_at = Carbon::now();
            $parcel->save();

            if ($request->note) {
                $note           = new Parcelnote();
                $note->parcelId = $request->hidden_id;
                $note->note     = $request->note;
                $note->save();
            }

// $pnote = Parceltype::find($request->status);

// $note = new Parcelnote();

// $note->parcelId = $request->hidden_id;

// $note->note = "Your parcel ".$pnote->title;

// $note->save();

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

                // if ($request->note != null) {
                //     $note           = new Parcelnote();
                //     $note->parcelId = $request->hidden_id;
                //     $note->note     = 'Parcel delivered successfully';
                //     $note->save();
                // }

                // $merchantinfo = Merchant::find($parcel->merchantId);

                // if ($merchantinfo->emailAddress != null) {
                //     $data = [
                //         'contact_mail' => $merchantinfo->emailAddress,
                //         'trackingCode' => $parcel->trackingCode,
                //     ];
                //     $send = Mail::send('frontEnd.emails.percelcancel', $data, function ($textmsg) use ($data) {
                //         $textmsg->from('info@zuri.express');
                //         $textmsg->to($data['contact_mail']);
                //         $textmsg->subject('Percel Cancelled Notification');
                //     });
                // }

            } elseif ($request->status == 6) {
                if ($parcel->payment_option == 2) {
                    $charge      = Deliverycharge::find($parcel->orderType);
                    $codcharge   = ($request->partial_payment * $charge->cod) / 100;
                    $parcel->cod = $request->partial_payment;

                    $amount = $request->partial_payment - ($codcharge + $parcel->deliveryCharge);

                    $parcel->merchantAmount = $amount;
                    $parcel->merchantDue    = $amount;
                    $parcel->codCharge      = $codcharge;
                    $parcel->save();

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
        if($parcel->deliverymanId != '') {
            $deliveryman = Deliveryman::where('id', $parcel->deliverymanId)->first();

            $history = new History();
            $history->name = $parcel->recipientName;
            $history->parcel_id = $request->hidden_id;
            $history->done_by = $deliveryman->name;
            $history->status = $pstatus;
            $history->note = $request->note;
            $history->date = $parcel->updated_at;
            $history->save();

            try {
                $validMerchant = Merchant::find($parcel->merchantId);
                if (!empty($validMerchant)) {
                    \Illuminate\Support\Facades\Mail::to([
                        $validMerchant->emailAddress
                    ])->send(new ParcelStatusUpdateEmail($validMerchant, $parcel, $history));
                }
            } catch (\Exception $exception) {
                Log::info('DeliveryMan Parcel status update mail error: ' . $exception->getMessage());
            }
        }

            return ["success" => true, "message" => "Parcel information update successfully!"];
        // }

    }

    public function pickups(Request $request, $startFrom) {
        $id        = $request->header('id');
        $show_data = DB::table('pickups')
            ->join('merchants', 'merchants.id', '=', 'pickups.merchantId')
            ->where('pickups.deliveryman', $id)
            ->whereNotIn('pickups.status', [1])
            ->orderBy('pickups.id', 'DESC')
            ->select('pickups.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')

// ->skip($startFrom)
        // ->take(20)
            ->get();

        return $show_data;
    }

    public function pickup($parcelId, Request $request) {
        $id        = $request->header('id');
        $show_data = DB::table('pickups')
            ->join('merchants', 'merchants.id', '=', 'pickups.merchantId')
            ->where('pickups.deliveryman', $id)
            ->where('pickups.id', $parcelId)
            ->orderBy('pickups.id', 'DESC')
            ->select('pickups.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
            ->get();

        return $show_data;
    }

    public function pickupStatusUpdate(Request $request) {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $pickup         = Pickup::find($request->hidden_id);
        $pickup->status = $request->status;
        $pickup->save();

        return ["success" => true, "message" => "Pickup status update successfully!"];
    }

}
