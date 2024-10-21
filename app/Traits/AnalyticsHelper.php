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
    protected function getOrCreateDailyAnalytics()
    {
        return Analytics::whereDate('created_at', now()->toDateString())->first() ?? Analytics::create(['created_at' => now()]);
    }

    protected function updateAnalytics($field, $amount)
    {
        $analytics = $this->getOrCreateDailyAnalytics();
        $analytics->{$field} += $amount;
        $analytics->save();
    }

    protected function updateOrderAnalytics($amount, $isRefund = false)
    {
        $analytics = $this->getOrCreateDailyAnalytics();

        if ($isRefund) {
            $analytics->total_earning -= $amount;
            $analytics->total_refunded += $amount;
            // $analytics->total_orders--; // uncomment if you want update total_orders count when order canceled
        } else {
            $analytics->total_earning += $amount;
            $analytics->total_orders++;
        }

        $analytics->save();
    }

    protected function updateProductAnalytics($isAdded = true)
    {
        $this->updateAnalytics('total_products', $isAdded ? 1 : -1);
    }

    protected function updateUserAnalytics($isAdded = true)
    {
        $this->updateAnalytics('total_users', $isAdded ? 1 : -1);
    }

    protected function updateCategoryAnalytics($isAdded = true)
    {
        $this->updateAnalytics('total_categories', $isAdded ? 1 : -1);
    }

    protected function getAnalyticsForDays($days)
    {
        return Analytics::whereBetween('created_at', [now()->subDays($days), now()])->get();
    }
}
