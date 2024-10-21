<?php

namespace App\Http\Resources\Control;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductImagesResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'cover_image' => asset("cover/{$this->cover_image}"),
            'discount' => $this->discount,
            'categories' => $this->categories,
            'images' => ProductImagesResource::collection($this->images),
            'subcategories' => $this->subcategores,
            'stock' => $this->details->pluck('stock')->sum(),
        ];
    }
}
