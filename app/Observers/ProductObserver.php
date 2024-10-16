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
        $analytics = Analytics::first() ?? new Analytics();
        $analytics->total_products++;
        $analytics->updateLastUpdate();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $analytics = Analytics::first() ?? new Analytics();
        $analytics->total_products--;
        $analytics->updateLastUpdate();
    }
}
