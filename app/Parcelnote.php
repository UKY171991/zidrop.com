<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parcelnote extends Model
{
    public function notes(){
        return $this->hasOne('App\Note','id','note');
    }

    protected $fillable = [
        'parcelId',
        'note'
    ];
}
