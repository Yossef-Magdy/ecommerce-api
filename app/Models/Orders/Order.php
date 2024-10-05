<?php

namespace App\Models\Orders;

use App\Models\Shipping\Shipping;
use App\Models\User;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'paid_amount',
        'outstanding_amount',
        'token',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(Shipping::class);
    }

    public function orderCoupon(): HasOne
    {
        return $this->hasOne(OrderCoupon::class, 'order_id', 'id')->with('coupon');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
