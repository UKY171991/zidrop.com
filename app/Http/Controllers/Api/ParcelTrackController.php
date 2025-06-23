<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Parcel;
use App\Parcelnote;

class ParcelTrackController extends Controller
{
    /**
     * Track a parcel and return all details.
     * GET /api/track/parcel/{trackid}
     */
    public function track($trackid)
    {
        $parcel = Parcel::with([
            'merchant',
            'pickupcity',
            'deliverycity',
            'pickuptown',
            'deliverytown',
            'parcelnote.notes'
        ])->where('trackingCode', 'LIKE', '%' . $trackid . "%")
        ->orderBy('id', 'DESC')
        ->first();

        if ($parcel) {
            // Timeline: all notes for this parcel, ordered by created_at ASC
            $timeline = \App\Parcelnote::where('parcelId', $parcel->id)
                ->with('notes')
                ->orderBy('created_at', 'ASC')
                ->get()
                ->map(function($note) {
                    return [
                        'time' => $note->created_at ? $note->created_at->format('h:i A') : null,
                        'date' => $note->created_at ? $note->created_at->format('M d, Y') : null,
                        'status' => $note->notes->title ?? $note->note,
                        'raw_note' => $note->note,
                    ];
                });

            // Human-readable mapping for ambiguous fields
            $merchant = $parcel->merchant;
            if ($merchant) {
                $merchant->paymentMethodText = $merchant->paymentMethod == 1 ? 'Bank' : ($merchant->paymentMethod == 2 ? 'Mobile' : 'Unknown');
                $merchant->withdrawalText = $merchant->withdrawal == 1 ? 'Manual' : ($merchant->withdrawal == 2 ? 'Auto' : 'Unknown');
                $merchant->agreeText = $merchant->agree == 1 ? 'Agreed' : ($merchant->agree == 0 ? 'Not Agreed' : 'Unknown');
                $merchant->statusText = $merchant->status == 1 ? 'Active' : ($merchant->status == 0 ? 'Inactive' : 'Unknown');
            }
            if ($parcel->pickupcity) {
                $parcel->pickupcity->statusText = $parcel->pickupcity->status == 1 ? 'Active' : ($parcel->pickupcity->status == 0 ? 'Inactive' : 'Unknown');
            }
            if ($parcel->deliverycity) {
                $parcel->deliverycity->statusText = $parcel->deliverycity->status == 1 ? 'Active' : ($parcel->deliverycity->status == 0 ? 'Inactive' : 'Unknown');
            }
            if ($parcel->pickuptown) {
                $parcel->pickuptown->statusText = $parcel->pickuptown->status == 1 ? 'Active' : ($parcel->pickuptown->status == 0 ? 'Inactive' : 'Unknown');
            }
            if ($parcel->deliverytown) {
                $parcel->deliverytown->statusText = $parcel->deliverytown->status == 1 ? 'Active' : ($parcel->deliverytown->status == 0 ? 'Inactive' : 'Unknown');
            }

            // Rider info (if available)
            $rider = null;
            if ($parcel->deliverymen) {
                $rider = [
                    'name' => $parcel->deliverymen->name ?? null,
                    'phone' => $parcel->deliverymen->phone ?? null,
                ];
            }

            $data = [
                'id' => $parcel->id,
                'trackingCode' => $parcel->trackingCode,
                'invoiceNo' => $parcel->invoiceNo,
                'status' => $parcel->status,
                'created_at' => $parcel->created_at,
                'merchant' => $merchant,
                'pickup_city' => $parcel->pickupcity,
                'delivery_city' => $parcel->deliverycity,
                'pickup_town' => $parcel->pickuptown,
                'delivery_town' => $parcel->deliverytown,
                'recipientName' => $parcel->recipientName,
                'recipientAddress' => $parcel->recipientAddress,
                'recipientPhone' => $parcel->recipientPhone,
                'product' => [
                    'name' => $parcel->productName,
                    'description' => $parcel->note,
                    'weight' => $parcel->productWeight,
                    'color' => $parcel->productColor,
                    'qty' => $parcel->productQty,
                ],
                'charges' => [
                    'merchant_amount' => $parcel->merchantAmount,
                    'delivery_charge' => $parcel->deliveryCharge,
                    'cod_charge' => $parcel->codCharge,
                    'tax' => $parcel->tax,
                    'insurance' => $parcel->insurance,
                    'total_amount' => $parcel->cod,
                ],
                'timeline' => $timeline,
                'rider' => $rider,
                'last_update' => $parcel->updated_at,
            ];
            return response()->json(['success' => true, 'message' => 'Parcel Found', 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'Parcel not found', 'data' => null]);
        }
    }
}
