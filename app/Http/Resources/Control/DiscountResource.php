<?php

namespace App\Http\Resources\Control;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->isExpired()) {
            $this->close();
        }
        return [
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
            'value' => $this->value,
            'expiry_date' => $this->expiry_date,
        ];
    }
}
