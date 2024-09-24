<?php

namespace App\Http\Resources;

use App\Models\Products\ProductImage;
use App\Models\Products\ProductOption;
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
            'reviews' => $this->reviews->count(),
            'categories' => $this->categories,
            'sub_categories' => $this->subCateroies,
            'discount' => $this->discount,
            'cover_image' => Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}"),
            'images' => ProductImagesResource::collection($this->images),
            'attributes' => $this->productOptions
                ->load('attributeOption.attribute')
                ->groupBy(fn($productOption) => $productOption->attributeOption->attribute->name)
                ->map(fn($options, $attributeName) => [
                    'attribute' => $attributeName,
                    'options' => $options->map(function ($option) use ($attributeName) {
                        return $attributeName === 'price'
                            ? (float) $option->attributeOption->value
                            : ($attributeName === 'stock' ? (int) $option->attributeOption->value : $option->attributeOption->value);
                    })->all()
                ])
                ->values()
                ->all(),
        ];
    }
}
