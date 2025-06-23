<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parcel;
use App\Merchant;
use Illuminate\Support\Facades\DB;

class ApiMerchantSummary extends Controller
{
    /**
     * Get merchant dashboard summary (Next Payout, Returned-To-Merchant Due, Overall Paid Amount)
     * GET /api/merchant/dashboard/summary
     * Header: id (merchant id)
     */
    public function summary(Request $request)
    {
        $merchantId = $request->header('id');
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id header required.'], 400);
        }
        // Next Payout Amount: sum of merchantDue for delivered/partial delivered parcels
        $nextPayout = Parcel::where('merchantId', $merchantId)
            ->whereIn('status', [4, 6])
            ->sum('merchantDue');
        // Returned-To-Merchant Due: sum of deliveryCharge+tax+insurance for returned-to-merchant parcels
        $retParcelType = \App\Parceltype::where('slug', 'return-to-merchant')->first();
        $retDue = 0;
        if ($retParcelType) {
            $retDue = Parcel::where('merchantId', $merchantId)
                ->where('status', $retParcelType->id)
                ->sum(DB::raw('deliveryCharge + tax + insurance'));
        }
        // Overall Paid Amount: sum of merchantPaid
        $overallPaid = Parcel::where('merchantId', $merchantId)->sum('merchantPaid');
        return response()->json([
            'success' => true,
            'next_payout_amount' => $nextPayout,
            'returned_to_merchant_due' => $retDue,
            'overall_paid_amount' => $overallPaid
        ]);
    }
}
