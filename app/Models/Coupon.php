<?php

namespace App\Models;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'coupon_code',
        'uses_count',
        'status',
        'discount_type',
        'discount_value',
        'expiry_date',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_coupon', 'coupon_id', 'order_id');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function decrementUsesCount(): void
    {
        $this->uses_count--;
        $this->save();
    }

    public function isUsed(): bool
    {
        return $this->uses_count === 0;
    }
}
