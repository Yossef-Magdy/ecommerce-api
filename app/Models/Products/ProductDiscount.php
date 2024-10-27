<?php

namespace App\Models\Products;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'products_discount';

    protected $fillable = [
        'status',
        'expiry_date',
        'type',
        'value',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function isExpired(): bool
    {
        return !Carbon::make($this->expiry_date)->gt(Carbon::now());
    }

    // change status to closed before expiry
    public function close()
    {
        $this->status = 'closed';
        $this->save();
    }
}
