<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->id,
            'size' => $this->productDetail->size,
            'color' => $this->productDetail->color,
            'material' => $this->productDetail->material,
            'quantity' => $this->quantity,
            'price' => (double) $this->productDetail->price,
            'total_price' => (double) $this->total_price,
            'product' => [
                'product_detail_id' => $this->productDetail->id,
                'product_id' => $this->productDetail->product->id,
                'name' => $this->productDetail->product->name,
                'description' => $this->productDetail->product->description,
                'image' => asset("cover/{$this->productDetail->product->cover_image}"),
                'price' => (double) $this->productDetail->product->price,
            ],
        ];
    }
}
