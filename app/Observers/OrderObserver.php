<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\Orders\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class OrderObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $this->clearCahe($order);
        $this->updateAnalyticsOnOrderCreated($order);
    }

    /**
     * Handle the Order "refunded" event.
     */
    public function refunded(Order $order): void
    {
        $this->clearCahe($order);
        $this->updateAnalyticsOnOrderRefunded($order);
    }

    protected function updateAnalyticsOnOrderCreated(Order $order): void
    {
        $analytics = Analytics::first() ?? new Analytics();

        $analytics->total_orders++;
        $analytics->total_earning += $order->payment->amount;

        if ($analytics->isUpdatedToday()) {
            $analytics->today_orders++;
        } else {
            $analytics->today_orders = 1;
        }

        if ($analytics->isUpdatedLastMonth()) {
            $analytics->month_orders = 1;
        } else {
            $analytics->month_orders++;
        }

        if ($analytics->isUpdatedLastYear()) {
            $analytics->year_orders = 1;
        } else {
            $analytics->year_orders++;
        }

        $analytics->updateLastUpdate();
    }

    protected function updateAnalyticsOnOrderRefunded(Order $order): void
    {
        $analytics = Analytics::first();

        if ($analytics) {
            $analytics->total_earning -= $order->payment->amount;
            $analytics->total_refunded += $order->payment->amount;

            $analytics->updateLastUpdate();
        }
    }
    
    private function clearCahe($order)
    {
        $month = $order->created_at->format('m');
        $year = $order->created_at->format('Y');

        $cacheKey = "sales_data_{$year}_{$month}";
        return Cache::forget($cacheKey);
    }
}
