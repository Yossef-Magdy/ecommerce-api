<?php

namespace App\Models\Products;

use App\Models\Categories\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSubCategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "product_subcategory";
    
    protected $fillable = [
        'product_id',
        'subcategory_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
}
