<?php

namespace App\Models\Shipping;

use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shipping';

    protected $fillable = [
        'method',
        'status',
        'fee',
        'order_id',
        'shipping_details_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function shippingDetails()
    {
        return $this->belongsTo(ShippingDetails::class, 'shipping_details_id', 'id');
    }
}
