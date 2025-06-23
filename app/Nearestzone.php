<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nearestzone extends Model {
    public function state() {
        return $this->belongsTo(Deliverycharge::class, 'state', 'id');
    }
}
