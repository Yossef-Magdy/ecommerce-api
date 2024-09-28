<?php

namespace App\Models;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orber(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function incrementUsesCount(): void
    {
        $this->uses_count++;
        $this->save();
    }

    public function isUsed(): bool
    {
        return $this->uses_count > 0;
    }
}
