<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsDetails extends Model
{
    use HasFactory;

    protected $table = 'static_details';

    protected $fillable = [
        'type',
        'total_delivery', // Add this line
        'total_customers',
        'total_years',
        'total_member',
        'is_active',
    ];
}
