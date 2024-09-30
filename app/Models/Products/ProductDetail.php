<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'color', 'size', 'material', 'stock', 'price'];
    protected $hidden = ['product_id', 'created_at', 'updated_at'];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
