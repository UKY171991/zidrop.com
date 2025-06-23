<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Parcel;
use App\Deliveryman;



class DeliverymanCommission extends Model
{
    use HasFactory;
    protected $table = 'deliveryman_commissions'; // Specify the table name

    // public function parcel() {
    //     return $this->belongsTo(Parcel::class, 'parcel_id', 'id');
    // }

    public function deliveryman() {
        return $this->belongsTo(Deliveryman::class, 'deliveryman_id');
    }
    public function getParcelsAttribute()
    {
        $parcelIds = explode(',', $this->parcel_ids); // Convert string to array

        return Parcel::whereIn('id', $parcelIds)->get(); // Get parcels
    }


}
