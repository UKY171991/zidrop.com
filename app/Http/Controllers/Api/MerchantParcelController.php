<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parcel;

class MerchantParcelController extends Controller
{
    // Get paginated parcels for a merchant
    public function index(Request $request)
    {
        $merchantId = $request->header('id') ?? $request->query('merchant_id');
        $perPage = $request->query('per_page', 20);
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id required.'], 400);
        }
        $parcels = Parcel::where('merchantId', $merchantId)
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
        return response()->json(['success' => true, 'parcels' => $parcels]);
    }
} 