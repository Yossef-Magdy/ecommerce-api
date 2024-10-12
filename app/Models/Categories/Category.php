<?php

namespace App\Models\Categories;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Categories\Subcategory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name'];

    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
