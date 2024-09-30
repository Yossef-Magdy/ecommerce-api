<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'products_discount';

    protected $fillable = [
        'status',
        'expiry_date',
        'type',
        'value',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
