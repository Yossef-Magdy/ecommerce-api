<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BestSellerResource;
use App\Models\Orders\OrderItem;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller
{
    public function index()
    {
        $bestSellingProducts = OrderItem::select('product_detail_id', DB::raw('SUM(quantity) as total_quantity'))
        ->groupBy('product_detail_id')
        ->orderBy('total_quantity', 'desc')
        ->limit(10)
        ->with([
            'productDetail.product' => function ($query) {
                $query->select('id', 'slug', 'name', 'price', 'cover_image');
            },
            'productDetail.product.discount' => function ($query) {
                $query->select('id', 'product_id', 'status', 'type', 'value');
            },
            'productDetail.product.details' => function ($query) {
                $query->select('id', 'product_id', 'color');
            },
            'productDetail.product.images' => function ($query) {
                $query->select('id', 'product_id', 'image_url');
            },
        ])
        ->get()
        ->unique('productDetail.product.id');

        return response()->json(BestSellerResource::collection($bestSellingProducts), 200);
    }
}
