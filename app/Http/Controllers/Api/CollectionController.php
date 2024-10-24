<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request['category'];
        $products = Product::whereHas('categories', function ($query) use ($category) {
            $query->where('name', $category);
        })->orWhereHas('subcategories', function ($query) use ($category) {
            $query->where('name', $category);
        })->distinct()->paginate(10);
        return ProductResource::collection($products);
    }
}
