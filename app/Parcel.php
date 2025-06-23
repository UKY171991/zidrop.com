<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UpDistrict;
use App\Area;
use App\Nearestzone;
use App\Parcelnote;
use App\City;
use App\Town;
use App\Parceltype;
use App\Merchant;
use App\Deliveryman;
use App\Agent;
use App\P2pParcels;


class Parcel extends Model {
    protected $fillable = [
        'invoiceNo',
        'recipientName',
        'recipientAddress',
        'recipientPhone',
        'merchantId',
        'merchantAmount',
        'merchantDue',
        'cod',
        'productWeight',
        'note',
        'trackingCode',
        'deliveryCharge',
        'codCharge',
        'orderType',
        'codType',
        'percelType',
        'status',
        'reciveZone',
        'pay_return',
        'package_value',
        'tax',
        'insurance',
        'productName',
        'productQty',
        'productColor',
        'parcel_source',
        'pickup_cities_id',
        'delivery_cities_id',
        'pickup_town_id',
        'delivery_town_id',
        'agentId',
        'payment_option',
        'discounted_value',
        'p2p_payment_option',
        'agentAmount',
    ];
    

    public function deliverymen() {
        return $this->hasOne(Deliveryman::class, 'id', 'deliverymanId');
    }

    public function pickupmen() {
        return $this->hasOne(Deliveryman::class, 'id', 'pickupmanId');
    }

    // public function merchant()
    // {
    //     return $this->belongsTo(Merchant::class, 'merchantId')
    //       ->withDefault([
    //         'companyName' => 'N/F',
    //         'phoneNumber' => 'N/F',
    //         ]);
    // }


    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchantId')
            ->withDefault(function ($merchant, $parent) {
                // Check the parcel_source value from the parcel table
                $parcelSource = $parent->parcel_source;
    
                if ($parcelSource === 'p2p') {
                    // Use Eloquent relationship to fetch custom data
                    $customData = $parent->p2pParcel()->first();
    
                    if ($customData) {
                        $merchant->firstName = $customData->sender_name ?? 'N/A';
                        $merchant->lastName = '';
                        $merchant->emailAddress = $customData->sender_email ?? 'N/A';
                        $merchant->phoneNumber = $customData->sender_mobile ?? 'N/A';
                        $merchant->companyName = 'P2P';
                    } else {
                        // Fallback defaults if p2p_parcels entry is missing
                        $merchant->firstName = 'N/F';
                        $merchant->lastName = 'N/F';
                        $merchant->emailAddress = 'N/F';
                        $merchant->phoneNumber = 'N/F';
                        $merchant->companyName = 'N/F';
                    }
                } else {
                    // Default values for non-p2p sources
                    $merchant->firstName = $merchant->firstName ?? 'N/F';
                    $merchant->lastName = $merchant->lastName ?? 'N/F';
                    $merchant->emailAddress = $merchant->emailAddress ?? 'N/F';
                    $merchant->phoneNumber = $merchant->phoneNumber ?? 'N/F';
                    $merchant->companyName = $merchant->companyName ?? 'N/F';
                }
            });
    }
    
    public function p2pParcel()
    {
        return $this->hasOne(P2pParcels::class, 'parcel_id', 'id')->withDefault([
            'sender_name' => 'N/F',
          
        ]);
    }
    public function getMerchantOrSenderDetails()
    {
        // Check if merchant data is available
        if ($this->merchant) {
            // Return merchant details as an object
            return (object)[
                'firstName' => $this->merchant->firstName,
                'lastName' => $this->merchant->lastName,
                'phoneNumber' => $this->merchant->phoneNumber,
                'emailAddress' => $this->merchant->emailAddress,
                'companyName' => $this->merchant->companyName,
                'pickLocation' => $this->merchant->pickLocation,
            ];
        } else {
            // Split sender_name into name parts
            if ($this->p2pParcel) {
                $nameParts = explode(' ', $this->p2pParcel->sender_name);

                // If there's only one name part, assign it to both firstName and lastName
                if (count($nameParts) === 1) {
                    $firstName = $lastName = $nameParts[0]; // Same for both
                } else {
                    $firstName = $nameParts[0]; // First part as first name
                    $lastName = implode(' ', array_slice($nameParts, 1)); // All other parts as last name
                }
            } else {
                $firstName = $lastName = 'NA';
            }

            // Return sender details as an object
            return (object)[
                'firstName' => $firstName ?? 'NA',
                'lastName' => $lastName ?? 'NA',
                'phoneNumber' => $this->p2pParcel->sender_mobile ?? 'NA',
                'emailAddress' => $this->p2pParcel->sender_email ?? 'NA',
                'companyName' => $this->p2pParcel->sender_name ?? 'NA',
                'pickLocation' => $this->p2pParcel->sender_address ?? 'NA',
            ];
        }
    }


    public function agent() {
        return $this->belongsTo(Agent::class, 'agentId');
    }
    public function parceltype() {
        return $this->belongsTo(Parceltype::class, 'status');
    }

    public function division() {
        return $this->belongsTo(Division::class);
    }
    public function district() {
        return $this->belongsTo(UpDistrict::class, 'up_district_id', 'id');
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function union() {
        return $this->belongsTo(Nearestzone::class, 'reciveZone', 'id');
    }

    public function parcelnote() {
        return $this->hasOne(Parcelnote::class, 'parcelId')->orderBy('id','desc');
    }
    public function pickupcity()
    {
        return $this->belongsTo(City::class, 'pickup_cities_id')->withDefault(['title' => 'N/A']); 
    }

    public function deliverycity()
    {
        return $this->belongsTo(City::class, 'delivery_cities_id')->withDefault(['title' => 'N/A']);
    }
    public function pickuptown()
    {
        return $this->belongsTo(Town::class, 'pickup_town_id')->withDefault(['title' => 'N/A']);
    }

    public function deliverytown()
    {
        return $this->belongsTo(Town::class, 'delivery_town_id')->withDefault(['title' => 'N/A']);
    }
}