<?php

namespace App\Observers;

use App\Models\Core\Analytics;
use App\Models\Shipping\Shipping;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class ShippingObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Shipping "updated" event.
     */
    public function updated(Shipping $shipping): void
    {
        $analytics = Analytics::first() ?? new Analytics();

        $paidAmount = $shipping->order?->payment?->paid_amount ?? 0;
        
        Log::info("Catched from Observer Shipping updated: {$shipping->order->id}");

        $analytics->total_earning -= $paidAmount;
        $analytics->total_refunded += $paidAmount;
        $analytics->updateLastUpdate();

        $shipping->order->payment->paid_amount -= $paidAmount;
        $shipping->order->payment->outstand_amount += $paidAmount;
        $shipping->order->payment->save();
    }
}
