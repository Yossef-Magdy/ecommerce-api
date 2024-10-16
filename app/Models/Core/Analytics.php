<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'total_products',
        'total_categories',
        'total_orders',
        'total_earning',
        'total_refunded',
        'total_users',
        'today_orders',
        'month_orders',
        'year_orders',
    ];

    public function updateLastUpdate()
    {
        $this->updated_at = now();
        $this->save();
    }
}
