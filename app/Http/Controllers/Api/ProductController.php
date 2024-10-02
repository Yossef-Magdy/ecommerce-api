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
        if ($request['count'] || $request['page']) {
            $products = Product::paginate($request['count'] ?? 10);
            return ProductResource::collection($products);
        }
        return ProductResource::collection(Product::all());
    }

    public function show(Product $product)
    {    
        return ProductDetailsResource::make($product);
    }
}
