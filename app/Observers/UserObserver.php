<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->updateAnalyticsOnUserCreated();
    }

    protected function updateAnalyticsOnUserCreated(): void
    {
        $analytics = Analytics::first() ?? new Analytics();
        $analytics->total_users++;
        $analytics->updateLastUpdate();
    }
}
