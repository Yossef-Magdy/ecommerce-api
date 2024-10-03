<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Models\Products\ProductDiscount;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount = $this->discount;
        if (!isset($discount)) {
            $discount = new ProductDiscount([
                'type' => 'fixed',
                'value' => 0,
            ]);
        } 
        return [
            'id' => $this->id,
            'slug' => Str::slug($this->name, '-'),
            'name' => $this->name,
            'price' => (double) $this->price,
            'discount_type' => $discount->type,
            'discount_value' => $discount->value,
            'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
            'hover_image' => asset("images/{$this->images[0]->image_url}"),
            'colors' => $this->details->pluck('color')->unique(),
        ];
    }
}