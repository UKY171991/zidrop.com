<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
use App\Parcel;

class P2pParcels extends Model
{
    use HasFactory;

    protected $table = 'p2p_parcels';
    protected $fillable = [
        'parcel_id',
        'sender_name',
        'sender_mobile',
        'sender_email',
        'sender_pickupcity',
        'sender_pickuptown',
        'sender_address',
        'recivier_name',
        'recivier_mobile',
        'recipient_pickupcity',
        'recipient_pickuptown',
        'recivier_address',
        'terms_condition',
        'payment_id',
    ];

    public function parcel()
{
    return $this->belongsTo(Parcel::class, 'parcel_id', 'id');
}
    

}
