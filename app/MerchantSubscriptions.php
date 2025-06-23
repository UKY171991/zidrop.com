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
use App\P2pParcels;
use App\SubscriptionsPlan;

class MerchantSubscriptions extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->belongsTo(SubscriptionsPlan::class, 'subs_pkg_id', 'id');
    }
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
}
