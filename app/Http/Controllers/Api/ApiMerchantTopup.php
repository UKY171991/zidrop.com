<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Topup;

class ApiMerchantTopup extends Controller
{
    /**
     * Get topup history for a merchant (for mobile dashboard)
     * GET /api/merchant/get/topup
     * Header: id (merchant id)
     */
    public function getTopup(Request $request)
    {
        $merchantId = $request->header('id');
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id header required.'], 400);
        }
        $topups = Topup::where('merchant_id', $merchantId)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'topups' => $topups
        ]);
    }


}
