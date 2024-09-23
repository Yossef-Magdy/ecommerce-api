<?php

namespace App\Models\Orders;

use App\Models\Shipping\Shipping;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'paid_amount',
        'outstanding_amount',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
