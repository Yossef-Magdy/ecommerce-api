<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function index()
    {
        return response()->json([
            'coupons' => Coupon::all(),
        ], 200);
    }

    public function show($id)
    {
        return response()->json([
            'coupon' => Coupon::find($id),
        ], 200);
    }

    public function store(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
        if ($coupon) {
            return response()->json([
                'message' => 'Coupon already exists',
            ], 409);
        }
        $coupon = Coupon::create($request->all());
        return response()->json([
            'coupon' => $coupon,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        // $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found',
            ], 404);
        }
        $coupon->update($request->all());
        return response()->json([
            'coupon' => $coupon,
        ], 200);
    }

    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        // $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found',
            ], 404);
        }
        $coupon->delete();
        return response()->json([
            'coupon' => $coupon,
        ], 200);
    }
}
