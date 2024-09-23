<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'expire_date',
        'discount_type',
        'discount_amount',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
