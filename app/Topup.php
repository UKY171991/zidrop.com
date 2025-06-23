<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model {
    protected $guarded = [];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }
}
