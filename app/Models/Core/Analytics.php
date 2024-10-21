<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'total_orders',
        'total_earning',
        'total_refunded',
        'total_users',
    ];
}
