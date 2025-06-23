<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\City;
use App\Town;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Get all cities
    public function cities()
    {
        return response()->json(City::select('id', 'title as name')->get());
    }

    // Get all towns for a given city
    public function towns($cityId)
    {
        $towns = Town::where('cities_id', $cityId)
            ->where('status', 1)
            ->select('id', 'title as name', 'slug', 'towncharge')
            ->get();
        return response()->json($towns);
    }

    // Get paginated parcels for a merchant
    public function merchantParcelList(Request $request)
    {
        $merchantId = $request->header('id') ?? $request->query('merchant_id');
        $perPage = $request->query('per_page', 20);
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id required.'], 400);
        }
        $parcels = \App\Parcel::where('merchantId', $merchantId)
            ->orderBy('id', 'DESC')
            ->paginate($perPage);
        return response()->json(['success' => true, 'parcels' => $parcels]);
    }
} 