<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreCouponRequest;
use App\Http\Requests\Control\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;

class CouponController extends Controller
{
    function __construct()
    {
        $this->modelName = "coupon";
        $this->authorizeResource(Coupon::class, 'coupon');
    }

    public function index()
    {
        return CouponResource::collection(Coupon::paginate(10));
    }

    public function store(StoreCouponRequest $request)
    {
        Coupon::create($request->validated());
        return $this->createdResponse();
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());
        return $this->updatedResponse();
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return $this->deletedResponse();
    }
}
