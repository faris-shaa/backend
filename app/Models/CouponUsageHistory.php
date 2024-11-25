<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsageHistory extends Model
{
    use HasFactory;
    protected $table = 'coupon_usage_history';
    protected $fillable = ['coupon_id', 'appuser_id'];
    public $timestamps = false;
}
