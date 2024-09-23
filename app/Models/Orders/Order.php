<?php

namespace App\Models\Orders;

use App\Models\Shipping\Shipping;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'paid_amount',
        'outstanding_amount',
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

    public function coupon(): HasOne
    {
        return $this->hasOne(Coupon::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
