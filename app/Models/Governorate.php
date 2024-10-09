<?php

namespace App\Models;

use App\Models\Shipping\ShippingDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Governorate extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'governorates';

    protected $fillable = ['name', 'fee'];

    public function shippingDetails(): HasMany
    {
        return $this->hasMany(ShippingDetail::class, 'governorate_id', 'id');
    }
}
