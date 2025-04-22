<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_usage',
        'used',
        'start_date',
        'end_date',
        'is_active',
    ];
}
