<?php

namespace App\Observers;

use App\Models\Shipping\Shipping;
use App\Traits\AnalyticsHelper;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ShippingObserver implements ShouldHandleEventsAfterCommit
{
    use AnalyticsHelper;
    /**
     * Handle the Shipping "updated" event.
     */
    public function updated(Shipping $shipping): void
    {
        // Check if shipping is canceled and update analytics
        if ($shipping->status === 'canceled') {
            // Get paid amount
            $paidAmount = $shipping->order?->payment?->paid_amount ?? 0;
    
            // Update analytics for refund
            $this->updateOrderAnalytics($paidAmount, true);
        
            // Update payment details
            $shipping->order->payment->paid_amount -= $paidAmount;
            $shipping->order->payment->outstanding_amount += $paidAmount;
            $shipping->order->payment->save();
        }
    }
}
