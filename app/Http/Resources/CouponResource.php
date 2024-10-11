<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
        if (Carbon::make($this->expiry_date)->lte(Carbon::now()) && $this->status == 'active') {
            $data = ['status' => 'expired'];
            $this->update($data);
        } else if (Carbon::make($this->expiry_date)->gt(Carbon::now()) && $this->status == 'expired') {
            $data = ['status' => 'active'];
            $this->update($data);
        }
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
