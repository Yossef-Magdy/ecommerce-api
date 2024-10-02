<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "method" => $this->method,
            "status" => $this->status,
            "fee" => (float) $this->fee,
            "shipping_detail" => new ShippingDetailResource($this->shippingDetails),
        ];
    }
}
