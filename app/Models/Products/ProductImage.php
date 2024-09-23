<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['product_id', 'image_url'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
