<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'slug' => Str::slug($this->name, '-'),
            'name' => $this->name,
            'stock' => $this->stock,
            'color' => $this->color,
            'discount' => $this->discount,
            'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
            'images' => ProductImagesResource::collection($this->images),
        ];
    }
}
