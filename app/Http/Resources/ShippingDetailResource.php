<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingDetailResource extends JsonResource
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
            'city' => $this->city,
            'address' => $this->address,
            'apartment' => $this->apartment,
            'postal_code' => $this->postal_code,
            'phone_number' => $this->phone_number,
            'is_default' => $this->is_default == 1 ? true : false,
            'governorate' => $this->governorate->name,
        ];
    }
}
