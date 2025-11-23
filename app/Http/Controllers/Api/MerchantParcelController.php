<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parcel;
use DB;
use App\Parceltype;

class MerchantParcelController extends Controller
{
    // Get paginated parcels for a merchant
    // public function index(Request $request)
    // {
    //     $merchantId = $request->header('id') ?? $request->query('merchant_id');
    //     $perPage = $request->query('per_page', 20);
    //     if (!$merchantId) {
    //         return response()->json(['success' => false, 'message' => 'Merchant id required.'], 400);
    //     }
    //     $parcels = Parcel::where('merchantId', $merchantId)
    //         ->orderBy('id', 'DESC')
    //         ->paginate($perPage);
    //     return response()->json(['success' => true, 'parcels' => $parcels]);
    // }
     public function index(Request $request)
{
    
    $merchantId =  $request->header('id') ?? $request->query('merchant_id');

    $query = DB::table('parcels')
        ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
        ->where('parcels.merchantId', $merchantId);
        

    // ✅ Optional filters
    if ($request->filled('tracking_id')) {
        $query->where('parcels.trackingCode', $request->query('tracking_id'));
    }

    if ($request->filled('name')) {
        $query->where('parcels.recipientName','like', '%'.$request->query('name').'%');
    }

    if ($request->filled('phone')) {
        $query->where('parcels.recipientPhone', $request->query('phone'));
    }

    // ✅ Select and paginate
    $allparcel = $query->select(
            'parcels.*',
            'merchants.firstName',
            'merchants.lastName',
            'merchants.phoneNumber',
            'merchants.emailAddress',
            'merchants.companyName',
            'merchants.status as mstatus',
            'merchants.id as mid'
        )
        ->orderBy('id', 'DESC')
        ->paginate(20)
        ->appends([
            'merchant_id' => $merchantId,
            'tracking_id' => $request->query('tracking_id'),
            'name'        => $request->query('name'),
            'phone'       => $request->query('phone'),
        ]);

    return response()->json([
        
        'allParcel' => $allparcel
        
    ]);
}
    // public function parcelListMonth(Request $request, $slug)
    // {
    //     $parceltype = Parceltype::where('slug', $slug)->first();
    //     $slug       = $slug;
    //     $merchantId = $request->query('merchant_id');
    //     // var_dump($merchantId);
    //     // exit();

    //     $allparcel = DB::table('parcels')
    //         ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
    //         ->where('parcels.merchantId', $merchantId)
    //         ->where('parcels.status', $parceltype->id)
    //         ->whereMonth('parcels.updated_at', now())
    //         ->whereYear('parcels.updated_at', now())
    //         ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
    //         ->orderBy('updated_at', 'DESC')
    //         // ->get();
    //         ->paginate(10)
    //         ->appends(['merchant_id' => $merchantId]);
    //     return response()->json([
    //         'slug' => $slug,
    //         'allParcel' => $allparcel,
    //         'parcelType' => $parceltype
    //     ]);
    // }
    public function parcelListMonth(Request $request, $slug)
{
    $parceltype = Parceltype::where('slug', $slug)->first();
    $merchantId = $request->query('merchant_id');

    $query = DB::table('parcels')
        ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
        ->where('parcels.merchantId', $merchantId)
        ->when($parceltype, function ($query, $parceltype) {
        return $query->where('parcels.status', $parceltype->id);
    })
        ->whereMonth('parcels.updated_at', now())
        ->whereYear('parcels.updated_at', now());

    // ✅ Optional filters
    if ($request->filled('tracking_id')) {
        $query->where('parcels.trackingCode', $request->query('tracking_id'));
    }

    if ($request->filled('name')) {
        $query->where('parcels.recipientName','like', '%'.$request->query('name').'%');
    }

    if ($request->filled('phone')) {
        $query->where('parcels.recipientPhone', $request->query('phone'));
    }

    // ✅ Select and paginate
    $allparcel = $query->select(
            'parcels.*',
            'merchants.firstName',
            'merchants.lastName',
            'merchants.phoneNumber',
            'merchants.emailAddress',
            'merchants.companyName',
            'merchants.status as mstatus',
            'merchants.id as mid'
        )
        ->orderBy('updated_at', 'DESC')
        ->paginate(10)
        ->appends([
            'merchant_id' => $merchantId,
            'tracking_id' => $request->query('tracking_id'),
            'name'        => $request->query('name'),
            'phone'       => $request->query('phone'),
        ]);

    return response()->json([
        'slug' => $slug,
        'allParcel' => $allparcel,
        'parcelType' => $parceltype
    ]);
}

    //  public function parcelList(Request $request, $slug)
    // {
    //     $parceltype = Parceltype::where('slug', $slug)->first();
    //     $slug       = $slug;
    //     $merchantId = $request->query('merchant_id');

    //     $allparcel = DB::table('parcels')
    //         ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
    //         ->where('parcels.merchantId', $merchantId)
    //         ->where('parcels.status', $parceltype->id)
            
    //         ->select('parcels.*', 'merchants.firstName', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName', 'merchants.status as mstatus', 'merchants.id as mid')
    //         ->orderBy('updated_at', 'DESC')
    //         // ->get();
    //          ->paginate(10)
    //         ->appends(['merchant_id' => $merchantId]);
    //     return response()->json([
    //         'slug' => $slug,
    //         'allParcel' => $allparcel,
    //         'parcelType' => $parceltype
    //     ]);
    // }

public function parcelList(Request $request, $slug)
{
    $parceltype = Parceltype::where('slug', $slug)->first();
    $merchantId = $request->query('merchant_id');

    $query = DB::table('parcels')
        ->join('merchants', 'merchants.id', '=', 'parcels.merchantId')
        ->where('parcels.merchantId', $merchantId)
        ->when($parceltype, function ($query, $parceltype) {
        return $query->where('parcels.status', $parceltype->id);
    });
        

    // ✅ Optional filters
    if ($request->filled('tracking_id')) {
        $query->where('parcels.trackingCode', $request->query('tracking_id'));
    }

    if ($request->filled('name')) {
        $query->where('parcels.recipientName','like', '%'.$request->query('name').'%');
    }

    if ($request->filled('phone')) {
        $query->where('parcels.recipientPhone', $request->query('phone'));
    }

    // ✅ Select and paginate
    $allparcel = $query->select(
            'parcels.*',
            'merchants.firstName',
            'merchants.lastName',
            'merchants.phoneNumber',
            'merchants.emailAddress',
            'merchants.companyName',
            'merchants.status as mstatus',
            'merchants.id as mid'
        )
        ->orderBy('updated_at', 'DESC')
        ->paginate(10)
        ->appends([
            'merchant_id' => $merchantId,
            'tracking_id' => $request->query('tracking_id'),
            'name'        => $request->query('name'),
            'phone'       => $request->query('phone'),
        ]);

    return response()->json([
        'slug' => $slug,
        'allParcel' => $allparcel,
        'parcelType' => $parceltype
    ]);
}
}
