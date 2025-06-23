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

class TransferHistory extends Model
{
    use HasFactory;
    // Fillable properties
    protected $fillable = [
        'parcel_ids',
        'name',
        'content',
        'transfer_type',
        'done_by',
        'status',
        'note',
        'date',
        'transfer_by',
        'batchnumber',
        'sealnumber',
        'tagnumber',
        'origin_hub_id',
        'destination_hub_id',
        'created_by',
    ];
    
    public function originhub()
    {
        return $this->belongsTo(Agent::class, 'origin_hub_id', 'id')->withDefault(['name' => 'N/A']);

    }
    public function destinationhub()
    {
        return $this->belongsTo(Agent::class, 'destination_hub_id', 'id')->withDefault(['name' => 'N/A']);

    }
    public function deliverymen() {
        return $this->hasOne(Deliveryman::class, 'id', 'deliveryman_id');
    }


}
