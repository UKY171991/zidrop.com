<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Parceltype;

class ParcelTypeDescribe extends Model
{
    use HasFactory;
    public function parcelType()
    {
        return $this->belongsTo('App\Parceltype', 'parcel_type_id');
    }
}
