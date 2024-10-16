<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\Products\Product;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ProductObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $analytics = Analytics::whereDate('created_at', date('Y-m-d'))->first();

        if (!$analytics) {
            $analytics = Analytics::create(['created_at' => now()]);
        }
        $analytics->total_products++;
        $analytics->updateLastUpdate();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $analytics = Analytics::whereDate('created_at', date('Y-m-d'))->first();

        if (!$analytics) {
            $analytics = Analytics::create(['created_at' => now()]);
        }
        $analytics->total_products--;
        $analytics->updateLastUpdate();
    }
}
