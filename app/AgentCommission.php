<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Parcel;
use App\Agent;


class AgentCommission extends Model
{
    use HasFactory;
    protected $table = 'agent_commissions'; // Specify the table name


    // public function parcel() {
    //     return $this->belongsTo(Parcel::class, 'parcel_id', 'id');
    // }

    public function agent() {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    public function getParcelsAttribute()
    {
        $parcelIds = explode(',', $this->parcel_ids); // Convert string to array

        return Parcel::whereIn('id', $parcelIds)->get(); // Get parcels
    }

}
