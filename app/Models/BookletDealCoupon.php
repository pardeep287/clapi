<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookletDealCoupon extends Model
{
    protected $fillable = ["coupon_code", "is_used"];
    public $timestamps = false;
}
