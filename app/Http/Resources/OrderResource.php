<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalOrderPrice = $this->orderItems->sum('total_price') + floatval($this->shipping->shippingDetails->governorate->fee);

        return [
            'id' => $this->id,
            'paid_amount' => (float) $this->payment->paid_amount,
            'outstanding_amount' => (float) $this->payment->outstanding_amount,
            'total_price' => $totalOrderPrice,
            'shipping' => new ShippingResource($this->shipping),
            'payment' => new PaymentResource($this->payment),
            'coupon' => $this->orderCoupon ? new CouponResource($this->orderCoupon->coupon) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
