<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'cover_image'];

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

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function subCateroies(): HasMany
    {
        return $this->hasMany(ProductSubcategory::class);
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'product_attribute_options');
    }
    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
}
