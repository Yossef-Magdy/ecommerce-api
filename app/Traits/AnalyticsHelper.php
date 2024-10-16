<?php

namespace App\Traits;

use App\Models\Categories\Category;
use App\Models\Core\Analytics;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Products\Product;
use App\Models\Shipping\Shipping;
use App\Models\User;
use Carbon\Carbon;

trait AnalyticsHelper
{
    protected function updateOrderAnalytics($amount, $isRefund = false)
    {
        $analytics = Analytics::whereDate('created_at', now()->toDateString())->first();

        if (!$analytics) return $this->InitializeAnalytics();

        if ($isRefund) {
            $analytics->total_earning -= $amount;
            $analytics->total_refunded += $amount;
        } else {
            $analytics->total_earning += $amount;
            $analytics->total_orders++;
            $analytics->today_orders++;
            $analytics->month_orders++;
            $analytics->year_orders++;
        }

        $analytics->updateLastUpdate();
    }

    protected function updateProductAnalytics($isAdded = true)
    {

        $analytics = Analytics::whereDate('created_at', now()->toDateString())->first();

        if (!$analytics) return $this->InitializeAnalytics();

        if ($isAdded) {
            $analytics->total_products++;
            $analytics->updateLastUpdate();
        } else {
            $analytics->total_products--;
            $analytics->updateLastUpdate();
        }
    }

    protected function updateUserAnalytics($isAdded = true)
    {

        $analytics = Analytics::whereDate('created_at', now()->toDateString())->first();

        if (!$analytics) return $this->InitializeAnalytics();

        if ($isAdded) {
            $analytics->total_users++;
            $analytics->updateLastUpdate();
        } else {
            $analytics->total_users--;
            $analytics->updateLastUpdate();
        }
    }

    protected function updateCategoryAnalytics($isAdded = true)
    {
        $analytics = Analytics::whereDate('created_at', now()->toDateString())->first();

        if (!$analytics) return $this->InitializeAnalytics();

        if ($isAdded) {
            $analytics->total_categories++;
            $analytics->updateLastUpdate();
        } else {
            $analytics->total_categories--;
            $analytics->updateLastUpdate();
        }
    }

    protected function InitializeAnalytics()
    {
        $analytics = Analytics::whereDate('created_at', now()->toDateString())->first();

        if (!$analytics) {
            $analytics = Analytics::create(['created_at' => now()]);
        }

        $analytics->total_products = Product::count();
        $analytics->total_categories = Category::count();
        $analytics->total_orders = Order::count();
        $analytics->total_users = User::count();

        $analytics->total_refunded = Shipping::where('status', 'canceled')
            ->with('order')
            ->get()
            ->sum(function ($shipping) {
                return $shipping->order->orderItems->sum('total_price');
            });

        $analytics->total_earning = Shipping::where('status', '!=', 'canceled')
            ->with('order')
            ->get()
            ->sum(function ($shipping) {
                return $shipping->order->orderItems->sum('total_price');
            });

        $analytics->today_orders = OrderItem::whereDate('created_at', Carbon::today())->count();
        $analytics->month_orders = OrderItem::whereMonth('created_at', Carbon::now()->month)->count();
        $analytics->year_orders = OrderItem::whereYear('created_at', Carbon::now()->year)->count();

        $analytics->save();

        return $analytics;
    }
}
