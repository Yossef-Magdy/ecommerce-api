<?php

namespace App\Observers;

use App\Models\Orders\Order;
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
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $this->clearCahe($order);
    }

    private function clearCahe($order) {
        $month = $order->created_at->format('m');
        $year = $order->created_at->format('Y');
    
        $cacheKey = "sales_data_{$year}_{$month}";
        return Cache::forget($cacheKey);
    }
}
