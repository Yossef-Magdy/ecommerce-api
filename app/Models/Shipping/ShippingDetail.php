<?php

namespace App\Models\Shipping;

use App\Models\Core\Governorate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingDetail extends Model
{
    use HasFactory;

    protected $table = 'shipping_details';

    protected $fillable = [
        'city',
        'address',
        'apartment',
        'postal_code',
        'phone_number',
        'governorate_id',
        'user_id',
        'is_default',
    ];

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
