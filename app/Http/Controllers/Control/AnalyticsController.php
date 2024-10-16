<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Categories\Category;
use App\Models\Core\Analytics;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use App\Models\Shipping\Shipping;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['data' => Analytics::first()]);
    }

    public function update(Request $request)
    {
        $analytics = Analytics::first() ?? new Analytics();

        $analytics->total_products = Product::count();
        $analytics->total_categories = Category::count();
        $analytics->total_orders = Order::count();
        $analytics->total_earning = OrderItem::sum('total_price');

        $analytics->total_refunded = Shipping::where('status', 'canceled')
            ->with('order')
            ->get()
            ->sum(function ($shipping) {
                return $shipping->order->orderItems->sum('total_price');
            });

        $analytics->total_users = User::count();

        $today = Carbon::today();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $analytics->today_orders = OrderItem::whereDate('created_at', $today)->count();
        $analytics->month_orders = OrderItem::whereMonth('created_at', $month)->count();
        $analytics->year_orders = OrderItem::whereYear('created_at', $year)->count();

        $analytics->save();

        return response()->json([
            'data' => $analytics,
        ]);
    }

    public function show(Request $request)
    {
        // Get month and year
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Get total sales
        $cacheKey = "sales_data_{$year}_{$month}";

        $salesData = Cache::remember($cacheKey, 3600, function () use ($year, $month) {
            return OrderItem::with(['productDetail' => function ($query) {
                $query->select('id', 'product_id', 'color', 'size', 'material', 'stock', 'price');
            }, 'order.orderCoupon.coupon' => function ($query) {
                $query->select('id', 'coupon_code', 'discount_type', 'discount_value');
            }])
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->select('id', 'order_id', 'product_detail_id', 'quantity', 'total_price', 'discount', 'created_at')
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
                            'discount' => number_format($item->discount, 2),
                            'total_price' => number_format($item->total_price, 2),
                            'created_at' => Carbon::parse($item->created_at)->setTimezone('Africa/Cairo'),
                            'used_coupon' => $item?->order?->orderCoupon?->coupon ?? 'No coupon used',
                        ];
                    })->sortByDesc('created_at')->values(),
                ];
            })->values();

        $totalProductsSold = $productsSold->count();

        return response()->json(compact('productsSold', 'totalItemsSold', 'totalProductsSold', 'totalSales'), 200);
    }
}
