<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\Products\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->updateAnalyticsOnProductCreated($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->updateAnalyticsOnProductDeleted($product);
    }

    protected function updateAnalyticsOnProductCreated(Product $product): void
    {
        $analytics = Analytics::first() ?? new Analytics();
        $analytics->total_products++;
        $analytics->updateLastUpdate();
    }
    protected function updateAnalyticsOnProductDeleted(Product $product): void
    {
        $analytics = Analytics::first() ?? new Analytics();
        $analytics->total_products--;
        $analytics->updateLastUpdate();
    }
}
