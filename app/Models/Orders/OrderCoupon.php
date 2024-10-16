<?php

namespace App\Models\Orders;

use App\Models\Core\Coupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCoupon extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'orders_coupon';

    protected $fillable = [
        'coupon_id',
        'order_id',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
