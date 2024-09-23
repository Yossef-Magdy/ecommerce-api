<?php

namespace App\Models\Orders;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'orders_coupon';

    protected $fillable = [
        'coupon_id',
        'order_id',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
