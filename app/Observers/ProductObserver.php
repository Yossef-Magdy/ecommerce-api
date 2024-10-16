<?php

namespace App\Observers;

use App\Models\Products\Product;
use App\Traits\AnalyticsHelper;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ProductObserver implements ShouldHandleEventsAfterCommit
{
    use AnalyticsHelper;
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->updateProductAnalytics(true);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->updateProductAnalytics(false);
    }
}
