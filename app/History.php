<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
     protected $guarded = [];
     protected $fillable = [
          'name',
          'parcel_id',
          'done_by',
          'status',
          'note',
          'date'
      ];
}