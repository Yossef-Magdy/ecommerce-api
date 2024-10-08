<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductDetailsResource;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $count = $request->get('count', 10);

        $products = Product::with(['categories', 'subcategories', 'details:product_id,color', 'images', 'discount'])->paginate($count);

        return ProductResource::collection($products);
    }

    public function show($identifier)
    {
        $product = Product::with(['categories', 'subcategories'])
            ->where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->firstOrFail();

        return new ProductDetailsResource($product);
    }

}
