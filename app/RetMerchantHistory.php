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

class RetMerchantHistory extends Model
{
    use HasFactory;

    protected $table = 'ret_merchant_histories';

    public function originhub()
    {
        return $this->belongsTo(Agent::class, 'origin_hub_id', 'id')->withDefault(['name' => 'N/A']);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id')->withDefault(['name' => 'N/A']);
    }
}
