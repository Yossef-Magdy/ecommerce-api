<?php

namespace App\Models;

use App\Models\Shipping\ShippingDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'governorates';

    protected $fillable = ['name'];

    public function shippingDetails()
    {
        return $this->hasMany(ShippingDetails::class, 'governorate_id', 'id');
    }
}
