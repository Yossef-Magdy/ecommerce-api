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

    public function isUpdatedToday(): bool
    {
        return $this->updated_at->isToday();
    }

    public function isUpdatedYesterday(): bool
    {
        return $this->updated_at->isYesterday();
    }

    public function isUpdatedLastWeek(): bool
    {
        return $this->updated_at->isLastWeek();
    }

    public function isUpdatedLastMonth(): bool
    {
        return $this->updated_at->isLastMonth();
    }

    public function isUpdatedLastYear(): bool
    {
        return $this->updated_at->isLastYear();
    }

    public function updateLastUpdate()
    {
        $this->updated_at = now();
        $this->save();
    }
}
