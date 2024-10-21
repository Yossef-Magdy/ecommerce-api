<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\AnalyticsHelper;

class UserObserver
{

    use AnalyticsHelper;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->updateUserAnalytics(true);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->updateUserAnalytics(false);
    }
}
