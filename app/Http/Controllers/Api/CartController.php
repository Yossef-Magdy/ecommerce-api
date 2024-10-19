<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCartRequest;
use App\Models\Core\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            $cart->delete();
            return response()->json($cart->items);
        };
        return response()->json([]);
    }

    public function store(StoreCartRequest $request)
    {
        $cart = Cart::firstOrNew(['user_id' => $request->user()->id]);
        $cart->items = $request->items;
        $cart->save();

        return response()->json($cart);
    }
}
