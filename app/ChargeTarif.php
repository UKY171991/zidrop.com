<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ChargeTarif;
use App\City;

class ChargeTarif extends Model
{
    use HasFactory;
    protected $fillable = [
        'pickup_cities_id',
        'delivery_cities_id',
        'deliverycharge',
        'extradeliverycharge',
        'codcharge',
        'tax',
        'insurance',
        'description',
        'created_at',
        'updated_at',
    ];

    public function pickupcity()
    {
        return $this->belongsTo(City::class, 'pickup_cities_id')->withDefault(['title' => 'N/A']); 
    }

    public function deliverycity()
    {
        return $this->belongsTo(City::class, 'delivery_cities_id')->withDefault(['title' => 'N/A']); 
    }
}
