<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'subcategories';

    protected $fillable = ['name', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
