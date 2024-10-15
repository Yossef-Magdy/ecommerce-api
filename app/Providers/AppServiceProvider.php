<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderRefunded;
use App\Events\ProductCreated;
use App\Events\ProductDeleted;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Observers\OrderObserver;
use App\Models\Orders\Order;
use App\Models\Products\Product;
use App\Observers\ProductObserver;
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
        Product::observe(ProductObserver::class);
    }

    protected $listen = [
        OrderCreated::class => [
            OrderObserver::class,
        ],
        OrderRefunded::class => [
            OrderObserver::class,
        ],
        UserCreated::class => [
            UserObserver::class,
        ],
        ProductCreated::class => [
            ProductObserver::class,
        ],
        ProductDeleted::class => [
            ProductObserver::class,
        ],
    ];
}
