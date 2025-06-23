<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RemainTopup;
use App\Parcel;

class MerchantWalletController extends Controller
{
    // Get paginated wallet usage history for a merchant
    public function history(Request $request)
    {
        $merchantId = $request->header('id') ?? $request->query('merchant_id');
        $perPage = $request->query('per_page', 10);
        if (!$merchantId) {
            return response()->json(['success' => false, 'message' => 'Merchant id required.'], 400);
        }
        $transactions = RemainTopup::where('merchant_id', $merchantId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $data = $transactions->map(function($tx) {
            $recipientInfo = 'Manual by Admin. (Deduction)';
            if ($tx->parcel_id) {
                $parcel = Parcel::find($tx->parcel_id);
                if ($parcel) {
                    $recipientInfo = $parcel->recipientName;
                    // Optionally add status (DELIVERED, CANCELLED, etc.)
                    if ($parcel->status == 4) {
                        $recipientInfo .= ' DELIVERED';
                    } elseif ($parcel->status == 3) {
                        $recipientInfo .= ' CANCELLED';
                    }
                }
            }
            return [
                'recipient_info' => $recipientInfo,
                'amount' => $tx->amount,
                'created_at' => $tx->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'transactions' => $data,
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
            ]
        ]);
    }

    /**
     * Get return invoice details for a merchant (API)
     * POST /api/merchant/payment/return-invoice-details
     * Params: merchant_id, update (date string)
     */
    public function returnInvoiceDetails(Request $request)
    {
        $merchantId = $request->input('merchant_id') ?? $request->header('id');
        $update = $request->input('update');
        if (!$merchantId || !$update) {
            return response()->json(['success' => false, 'message' => 'merchant_id and update (date) are required.'], 400);
        }
        $parcelIds = \App\Merchantreturnpayment::where('updated_at', $update)
            ->where('merchantId', $merchantId)
            ->pluck('parcelId')
            ->toArray();
        $parcels = \App\Parcel::whereIn('id', $parcelIds)
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype'])
            ->get();
        $merchant = \App\Merchant::find($merchantId);
        return response()->json([
            'success' => true,
            'parcels' => $parcels,
            'merchant' => $merchant,
            'update' => $update
        ]);
    }
} 