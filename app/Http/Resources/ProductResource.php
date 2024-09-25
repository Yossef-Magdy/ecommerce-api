<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
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
        $attributes = $this->productOptions
            ->load('attributeOption.attribute')
            ->whereNotIn('attributeOption.attribute.name', ['price', 'size'])
            ->map(function ($productOption) {
                $attributeName = $productOption->attributeOption->attribute->name;
                $value = $productOption->attributeOption->value;
                return [
                    'attribute' => $attributeName,
                    'value' => $attributeName === 'stock' ? (int)$value : $value,
                ];
            })
            ->groupBy('attribute')
            ->map(fn($options) => [
                'attribute' => $options->first()['attribute'],
                'options' => $options->pluck('value')->all()
            ])
            ->values()
            ->all();

        return [
            'id' => $this->id,
            'slug' => Str::slug($this->name, '-'),
            'name' => $this->name,
            'discount' => $this->discount,
            'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
            'images' => ProductImagesResource::collection($this->images),
            'attributes' => $attributes
        ];
    }
}