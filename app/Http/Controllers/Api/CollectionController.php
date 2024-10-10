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
        })
            ->orWhereHas('subcategories', function ($query) use ($category) {
                $query->where('name', $category);
            })
            ->distinct()
            ->paginate(10);
            
        $response = [
            // 'current_page' => $products->currentPage(),
            'data' => ProductResource::collection($products),
            // 'first_page_url' => $products->url(1),
            // 'from' => $products->firstItem(),
            // 'last_page' => $products->lastPage(),
            // 'last_page_url' => $products->url($products->lastPage()),
            // 'links' => [
            //     [
            //         'url' => $products->previousPageUrl(),
            //         'label' => '&laquo; Previous',
            //         'active' => !$products->onFirstPage(),
            //     ],
            //     [
            //         'url' => $products->nextPageUrl(),
            //         'label' => 'Next &raquo;',
            //         'active' => $products->hasMorePages(),
            //     ],
            // ],
            'next_page_url' => $products->nextPageUrl(),
            // 'path' => $products->path(),
            // 'per_page' => $products->perPage(),
            // 'prev_page_url' => $products->previousPageUrl(),
            // 'to' => $products->lastItem(),
            // 'total' => $products->total(),
        ];
        return response()->json($response);
    }
}
