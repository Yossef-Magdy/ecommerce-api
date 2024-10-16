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
        $totalOrderPrice = 0;

        foreach ($this->orderItems as $item) {
            $totalOrderPrice += $item['total_price'];
        }

        return [
            'id' => $this->id,
            'paid_amount' => (float) $this->payment->paid_amount,
            'outstanding_amount' => (float) $this->payment->outstanding_amount,
            'total_price' => $totalOrderPrice,
            'customer' => $this->shipping->shippingDetails->user,
            'shipping' => new ShippingResource($this->shipping),
            'payment' => new PaymentResource($this->payment),
            'coupon' => $this->orderCoupon ? new CouponResource($this->orderCoupon->coupon) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
