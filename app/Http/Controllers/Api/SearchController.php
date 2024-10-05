<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Search with name or description or color or material or category for products
        switch ($request->get('type')) {
            case 'product':
                $products = Product::where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('query') . '%')
                        ->orWhere('description', 'like', '%' . $request->get('query') . '%')
                        ->orWhereHas('details', function ($query) use ($request) {
                            $query->where('color', 'like', '%' . $request->get('query') . '%')
                                  ->orWhere('size', 'like', '%' . $request->get('query') . '%')
                                  ->orWhere('material', 'like', '%' . $request->get('query') . '%');
                        })
                        ->orWhereHas('categories', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->get('query') . '%');
                        });
                })->get();
                break;
            case 'category':
                $products = Product::whereHas('categories', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('query') . '%');
                })->get();
                break;
            case 'subcategory':
                $products = Product::whereHas('subcategories', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('query') . '%');
                })->get();
                break;
            case 'discount':
                $products = Product::whereHas('discount')->get();
                break;
            default:
                $products = [];
                break;
        }

        return $products;
    }
}
