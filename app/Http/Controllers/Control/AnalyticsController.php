<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Core\Analytics;
use App\Models\Orders\OrderItem;
use App\Traits\AnalyticsHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{

    use AnalyticsHelper;

    public function index(Request $request)
    {
        $analytics = $this->getOrCreateDailyAnalytics();
        return response()->json([
            "total_orders" => $analytics->total_orders,
            "total_earning" => number_format($analytics->total_earning, 2),
            "total_refunded" => number_format($analytics->total_refunded, 2),
            "total_users" => $analytics->total_users,
            "last_update" => $analytics->updated_at->diffForHumans(),
        ]);
    }

    public function show(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

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

        $productsSold = $salesData->groupBy('product_detail_id')->map(function ($group) {
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

    public function getStatisticsForDays(Request $request)
    {
        $days = $request->get('days', 7);

        $analyticsData = $this->getAnalyticsForDays($days);

        $statistics = $analyticsData->map(function ($analytics) {
            return [
                'date' => $analytics->created_at->toDateString(),
                'total_orders' => $analytics->total_orders,
                'total_earning' => number_format($analytics->total_earning, 2),
                'total_refunded' => number_format($analytics->total_refunded, 2),
                'total_users' => $analytics->total_users,
                'last_update' => $analytics->updated_at->diffForHumans(),
            ];
        });

        return response()->json([
            'days' => $days,
            'statistics' => $statistics,
        ], 200);
    }

    public function getStatisticsForDateRange(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        if (!$from || !$to) {
            return response()->json(['error' => 'Should send date from and to Ex:<br>From:2024-10-18<br>To:2024-10-20'], 400);
        }

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $analyticsData = Analytics::whereBetween('created_at', [$fromDate, $toDate])->get();

        $statistics = $analyticsData->map(function ($analytics) {
            return [
                'date' => $analytics->created_at->toDateString(),
                'total_orders' => $analytics->total_orders,
                'total_earning' => number_format($analytics->total_earning, 2),
                'total_refunded' => number_format($analytics->total_refunded, 2),
                'total_users' => $analytics->total_users,
                'last_update' => $analytics->updated_at->diffForHumans(),
            ];
        });

        return response()->json([
            'from' => $from,
            'to' => $to,
            'statistics' => $statistics,
        ], 200);
    }
}
