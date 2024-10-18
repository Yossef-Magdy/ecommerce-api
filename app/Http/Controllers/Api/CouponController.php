<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Models\Core\Coupon;

class CouponController extends Controller
{
    public function show(string $couponCode)
    {
        $coupon = Coupon::where("coupon_code", $couponCode)->firstOrFail();
        return CouponResource::make($coupon);
    }
}
