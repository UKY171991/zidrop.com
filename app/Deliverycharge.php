<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliverycharge extends Model
{
    //
    public function merchantcharge(){
        return $this->hasOne(Merchantcharge::class, 'packageId', 'id');
    }
}
