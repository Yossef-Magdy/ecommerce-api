<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = "product_subcategory";
    protected $fillable = [
        'product_id',
        'subcategory_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
