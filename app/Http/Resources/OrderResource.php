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
        return [
            'id' => $this->id,
            'paid_amount' => (float) $this->paid_amount,
            'outstanding_amount' => (float) $this->outstanding_amount,
            // 'user' => new UserResource($this->user),
            'token' => $this->token,
            'shipping' => new ShippingResource($this->shipping),
            'payment' => new PaymentResource($this->payment),
            'coupon' => $this->orderCoupon ? new CouponResource($this->orderCoupon->coupon) : null,
            'items' => OrderItemResource::collection($this->orderItems),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
