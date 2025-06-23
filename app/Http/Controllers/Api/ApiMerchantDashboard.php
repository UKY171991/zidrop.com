<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parcel;
use App\Merchant;

class ApiMerchantDashboard extends Controller
{
    /**
     * Get recent shipment status updates for a merchant (for mobile dashboard)
     * GET /api/merchant/dashboard/shipments
     * Header: id (merchant id)
     */
    public function recentShipments(Request $request)
    {
        $merchantId = $request->header('id');
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id header required.'], 400);
        }
        $parcels = Parcel::where('merchantId', $merchantId)
            ->orderBy('updated_at', 'DESC')
            ->limit(50)
            ->get(['id', 'recipientName', 'recipientAddress', 'recipientPhone', 'trackingCode', 'invoiceNo', 'productWeight', 'status', 'cod', 'updated_at']);
        return response()->json([
            'success' => true,
            'shipments' => $parcels
        ]);
    }
}
