<?php

namespace App\Models;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
