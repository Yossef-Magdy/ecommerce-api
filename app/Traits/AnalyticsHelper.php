<?php

namespace App\Traits;

use App\Models\Core\Analytics;

trait AnalyticsHelper
{
    protected function getOrCreateDailyAnalytics()
    {
        return Analytics::whereDate('created_at', now()->toDateString())->first() ?? Analytics::create(['created_at' => now()]);
    }

    protected function updateAnalytics($field, $amount)
    {
        $analytics = $this->getOrCreateDailyAnalytics();
        $analytics->{$field} += $amount;
        $analytics->save();
    }

    protected function updateOrderAnalytics($amount, $isRefund = false)
    {
        $analytics = $this->getOrCreateDailyAnalytics();

        if (!$isRefund) {
            $analytics->total_orders++;
        }

        $analytics->save();

        $this->updateEarningAnalytics($amount, $isRefund);
    }

    protected function updateEarningAnalytics($amount, $isRefund = false)
    {
        $analytics = $this->getOrCreateDailyAnalytics();

        if ($isRefund) {
            $analytics->total_earning -= $amount;
            $analytics->total_refunded += $amount;
        } else {
            $analytics->total_earning += $amount;
        }

        $analytics->save();
    }

    protected function backEarningAnalytics($amount)
    {
        $analytics = $this->getOrCreateDailyAnalytics();
        $analytics->total_earning += $amount;
        $analytics->total_refunded -= $amount;
        $analytics->save();
    }

    protected function updateUserAnalytics($isAdded = true)
    {
        $this->updateAnalytics('total_users', $isAdded ? 1 : -1);
    }

    protected function getAnalyticsForDays($days)
    {
        return Analytics::whereBetween('created_at', [now()->subDays($days), now()])->get();
    }
}
