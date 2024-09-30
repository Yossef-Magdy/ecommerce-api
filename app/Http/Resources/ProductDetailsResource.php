<?php

namespace App\Http\Resources;

use App\Models\Products\ProductDiscount;
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
        $cover = Str::startsWith($this->cover_image, 'http') ? $this->cover_image : asset("cover/{$this->cover_image}");
        $details = $this->details;
        $discount = $this->discount;
        if (!isset($discount)) {
            $discount = new ProductDiscount([
                'discount_type' => 'fixed',
                'discount_value' => 0,
            ]);
        } 
        return [
            'id' => $this->id,
            'slug' => Str::slug($this->name, '-'),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'details' => $details,
            'reviews_count' => $this->reviews->count(),
            'rating' => $averageRating,
            'categories' => CategoryResource::collection($this->categories),
            'sub_categories' => SubcategoryResource::collection($this->subcategories),
            'discount_type' => $discount->type,
            'discount_value' => $discount->value,
            'cover_image' => $cover,
            'images' => ProductImagesResource::collection($this->images),
        ];
    }

}
