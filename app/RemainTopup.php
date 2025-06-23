<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemainTopup extends Model {
    protected $guarded = [];

    public function parcel() {
        return $this->belongsTo(Parcel::class);
    }
    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }
}
