<?php

namespace App\Models\Shipping;

use App\Models\Governorate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetails extends Model
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
    ];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
