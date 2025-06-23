<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MerchantSubscriptions;

class Merchant extends Model {
    public function parcels() {
        return $this->hasMany(Parcel::class, 'merchantId', 'id');
    }

    protected $fillable = [
        'password',

    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function activeSubscription()
    {
        return $this->hasOne(MerchantSubscriptions::class, 'merchant_id', 'id')
            ->where('is_active', 1)
            ->with('plan');
    }

}
