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
use App\MerchantSubscriptions;

class SubscriptionsPlan extends Model
{
    use HasFactory;

    public function merchantSubscriptions()
    {
        return $this->hasMany(MerchantSubscriptions::class, 'subs_pkg_id', 'id');
    }


}
