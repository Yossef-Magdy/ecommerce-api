<?php

namespace App\Http\Resources;

use App\Models\Products\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Ramsey\Uuid\Type\Decimal;

class ProductDetailsResource extends JsonResource
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
          'slug' => Str::slug($this->name, '-'),
          'name' => $this->name,
          'description' => $this->description,
          'stock' => $this->stock,
          'price' =>  (float) $this->price,
          'color' => $this->color,
          'size' => $this->size,
          'discount' => $this->discount,
          'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
          'images' => ProductImagesResource::collection($this->images),
        ];
    }
}
