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
        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => (double) $this->price,
            'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
            'hover_image' => isset($this->images[0]) ? asset("images/{$this->images[0]->image_url}") : null,
            'colors' => $this->details->pluck('color')->unique(),
        ];

        if ($this->discount) {
            if ($this->discount->isExpired()) {
                $this->discount->close();
            } else {
                $data['discount_type'] = $this->discount->type;
                $data['discount_value'] = $this->discount->value;
            }
        };
    
        return $data;
    }
}