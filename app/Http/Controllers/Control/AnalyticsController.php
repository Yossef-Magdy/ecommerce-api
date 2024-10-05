<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Orders\OrderItem;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Get total sales
        $salesData = OrderItem::with('productDetail')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get total sales
        $totalSales = number_format($salesData->sum('total_price'), 2);
        
        // Get total items sold
        $totalItemsSold = $salesData->sum('quantity');

        // Get total products sold
        $totalProductsSold = $salesData->count();

        // Get products sold
        $productsSold = $salesData->groupBy('product_detail_id')
        ->map(function ($group) {
            // Get product detail
            $productDetail = $group->first()->productDetail;

            return [
                'product_id' => $productDetail->product_id,
                'name' => $productDetail->product->name,
                'color' => $productDetail->color,
                'size' => $productDetail->size,
                'material' => $productDetail->material,
                'in_stock' => $productDetail->stock,
                'product_price' => number_format($productDetail->price, 2),

                'total_quantity' => $group->sum('quantity'),
                'total_price' => number_format($group->sum('total_price'), 2),

                'details' => $group->map(function ($item) {
                    // Get used coupon
                    $usedCoupon = $item?->order?->orderCoupon?->coupon;

                    return [
                        'order_id' => $item->order_id,
                        'quantity' => $item->quantity,
                        'total_price' => number_format($item->total_price, 2),
                        'created_at' => $item->created_at,
                        'used_coupon' => $usedCoupon ? [
                            'coupon_code' => $usedCoupon?->coupon_code,
                            'discount_type' => $usedCoupon?->discount_type,
                            'discount_value' => $usedCoupon?->discount_value,
                        ] : 'No coupon used',
                    ];
                }),
            ];
        })->values();

        return response()->json(compact('productsSold', 'totalItemsSold', 'totalProductsSold', 'totalSales'), 200);
    }
}
