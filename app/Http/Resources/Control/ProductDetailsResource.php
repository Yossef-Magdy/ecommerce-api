<?php

namespace App\Http\Resources\Control;

use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductImagesResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\SubcategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviews = ProductReviewResource::collection($this->reviews);
        $averageRating = $reviews->avg('rating');
        $averageRating = $averageRating ? round($averageRating, 1) : 0;
        $details = ProductDetailResource::collection($this->details);

        $data = [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (double) $this->price,
            'stock' => $this->details->pluck('stock')->sum(),
            'details' => $details,
            'reviews' => $reviews,
            'rating' => $averageRating,
            'discount' => DiscountResource::make($this->discount),
            'categories' => CategoryResource::collection($this->categories),
            'subcategories' => SubcategoryResource::collection($this->subcategories),
            'cover_image' => asset("cover/{$this->cover_image}"),
            'images' => ProductImagesResource::collection($this->images),
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
