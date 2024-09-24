<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'product_attribute_options';

    protected $fillable = ['product_id', 'attribute_option_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeOption()
    {
        return $this->belongsTo(AttributeOption::class);
    }
}
