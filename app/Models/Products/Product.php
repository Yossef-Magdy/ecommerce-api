<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Categories\Category;
use App\Models\Categories\Subcategory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'cover_image', 'slug'];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function discount(): HasOne
    {
        return $this->hasOne(ProductDiscount::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->firstOrFail();
    }

    public function hasDiscount(): bool
    {
        return $this->discount()->exists();
    }
}
