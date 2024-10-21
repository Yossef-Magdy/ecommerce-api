<?php

namespace App\Observers;

use App\Models\Payments\Payment;
use App\Traits\AnalyticsHelper;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class PaymentObserver implements ShouldHandleEventsAfterCommit
{
    use AnalyticsHelper;
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        if ($payment->status === 'succeeded') {
            $this->updateOrderAnalytics($payment->paid_amount, false);
        }
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->status === 'succeeded' || $payment->status === 'completed') {
            if ($payment->outstanding_amount > 0) {
                $this->updateOrderAnalytics($payment->outstanding_amount, false);
                $payment->paid_amount += $payment->outstanding_amount;
                $payment->outstanding_amount = 0;
                $payment->save();
            }
        } else {
            $this->updateOrderAnalytics($payment->paid_amount, true);
        }
    }
}
