<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $image = $this->products->first()?->cover_image;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' =>  $image ? asset("cover/{$image}") : asset('default.png'),
            'category' => new CategoryResource($this->category),
        ];
    }
}
