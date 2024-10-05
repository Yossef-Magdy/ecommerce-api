<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Orders\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Get total sales
        $cacheKey = "sales_data_{$year}_{$month}";
        $salesData = Cache::remember($cacheKey, 60, function () use ($year, $month) {
            return OrderItem::with(['productDetail' => function ($query) {
                $query->select('id', 'product_id', 'color', 'size', 'material', 'stock', 'price');
            }, 'order.orderCoupon.coupon' => function ($query) {
                $query->select('id', 'coupon_code', 'discount_type', 'discount_value');
            }])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->select('id', 'order_id', 'product_detail_id', 'quantity', 'total_price', 'created_at')
                ->orderBy('created_at', 'asc')
                ->get();
        });

        $totalSales = number_format($salesData->sum('total_price'), 2);
        $totalItemsSold = $salesData->sum('quantity');

        $productsSold = $salesData->groupBy('product_detail_id')
            ->map(function ($group) {
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
                        return [
                            'order_id' => $item->order_id,
                            'quantity' => $item->quantity,
                            'total_price' => number_format($item->total_price, 2),
                            'created_at' => $item->created_at,
                            'used_coupon' => $item?->order?->orderCoupon?->coupon ?? 'No coupon used',
                        ];
                    }),
                ];
            })->values();

        $totalProductsSold = $productsSold->count();

        return response()->json(compact('productsSold', 'totalItemsSold', 'totalProductsSold', 'totalSales'), 200);
    }
}
