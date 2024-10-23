<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->updateStatus();
        return [
            'id' => $this->id,
            'coupon_code' => $this->coupon_code,
            'uses_count' => $this->uses_count,
            'status' => $this->status,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'expiry_date' => $this->expiry_date
        ];
    }
}
