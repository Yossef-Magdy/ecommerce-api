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
        $analytics = Analytics::whereDate('created_at', date('Y-m-d'))->first();

        if (!$analytics) {
            $analytics = Analytics::create(['created_at' => now()]);
        }
        $analytics->total_users++;
        $analytics->updateLastUpdate();
    }
}
