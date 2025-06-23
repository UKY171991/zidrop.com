<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;

class Deliveryman extends Model
{

    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id')->withDefault(['title' => 'N/A']); 
    }

    public function parcels() {
        return $this->hasMany(Parcel::class, 'deliverymanId', 'id');
    }
    
}
