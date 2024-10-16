<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\Orders\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class OrderObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
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
        Log::info("Catched from Observer order created: {$order->id}");
    }
}
