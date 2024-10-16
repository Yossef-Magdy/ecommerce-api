<?php

namespace App\Observers;

use App\Models\Categories\Category;
use App\Traits\AnalyticsHelper;

class CategoryObserver
{

    use AnalyticsHelper;

    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->updateCategoryAnalytics(true);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->updateCategoryAnalytics(false);
    }
}
