<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BestSellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->productDetail->product;
        return [
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => (float) $product->price,
            'discount' => $product->discount ? [
                'status' => $product->discount->status,
                'type' => $product->discount->type,
                'value' => $product->discount->value,
            ] : null,
            'colors' => $product->details?->map(fn($item) => $item->color)->unique()->values()->all(),
            'cover_image' => $product->cover_image ? asset("cover/{$product->cover_image}") : asset('cover/default.png'),
            'hover_image' => isset($product->images[0]) ? asset("images/{$product->images[0]}") : null,
        ];
    }
}
