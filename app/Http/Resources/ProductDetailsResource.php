<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $averageRating = $this->reviews->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 1) : 0;

        $attributes = $this->productOptions
            ->load('attributeOption.attribute')
            ->map(function ($productOption) {
                $attributeName = $productOption->attributeOption->attribute->name;
                $value = $productOption->attributeOption->value;
                return [
                    'attribute' => $attributeName,
                    'value' => in_array($attributeName, ['stock', 'price']) ? (float)$value : $value,
                ];
            })
            ->groupBy('attribute')
            ->map(fn($options) => [
                'attribute' => $options->first()['attribute'],
                'options' => $options->pluck('value')->all()
            ])
            ->values()
            ->all();

        $cover = Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}");
        
        return [
            'id' => $this->id,
            'slug' => Str::slug($this->name, '-'),
            'name' => $this->name,
            'description' => $this->description,
            'reviews_count' => $this->reviews->count(),
            'rating' => $averageRating,
            'categories' => $this->categories,
            'sub_categories' => $this->subCateroies,
            'discount' => $this->discount,
            'cover_image' => $cover,
            'images' => ProductImagesResource::collection($this->images),
            'attributes' => $attributes,
        ];
    }
}
