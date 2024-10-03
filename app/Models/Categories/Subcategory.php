<?php

namespace App\Models\Categories;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
    use HasFactory;
    protected $table = 'subcategories';

    protected $fillable = ['name', 'category_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_subcategory');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
