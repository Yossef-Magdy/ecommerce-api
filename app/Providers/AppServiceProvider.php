<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Observers\OrderObserver;
use App\Models\Orders\Order;
use App\Models\Payments\Payment;
use App\Observers\PaymentObserver;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-products', function (User $user) {
            return $user->hasRole('admin');
        });
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
