<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;
    protected $fillable = ['cities_id', 'title', 'slug', 'towncharge', 'created_at', 'updated_at'];

    public function city()
    {
        return $this->belongsTo('App\City', 'cities_id');
    }
}
