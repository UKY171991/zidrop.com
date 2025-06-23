<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\City;
use App\Town;


class Branch extends Model
{
    use HasFactory;

    
    public function city()
    {
        return $this->belongsTo(City::class, 'cities_id')->withDefault(['title' => 'N/A']); 
    }

    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id')->withDefault(['title' => 'N/A']); 
    }



}
