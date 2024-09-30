<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function show(Coupon $coupon)
    {
        return CouponResource::make($coupon);
    }
}
