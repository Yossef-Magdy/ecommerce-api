<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders\OrderItem;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller
{
    public function index()
    {
        // Get the best selling products from order_items table
        // And sum quantity of each product in order_items
        // Order by quantity desc and group by product_id
        $bestSellingProducts = OrderItem::select('product_detail_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_detail_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->with('productDetail')
            ->get();

        return response()->json($bestSellingProducts);
    }
}
