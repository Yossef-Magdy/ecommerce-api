<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_detail_id' => $this->id,
            'color' => $this->color,
            'size' => $this->size,
            'material' => $this->material,
            'stock' => $this->stock,
            'price' => (double) $this->price,
        ];
    }
}
