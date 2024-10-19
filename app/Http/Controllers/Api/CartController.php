<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCartRequest;
use App\Models\Core\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function saveCart(StoreCartRequest $request)
    {
        $cart = Cart::firstOrNew(['user_id' => $request->user()->id]);
        $cart->items = $request->items;
        $cart->save();

        return response()->json($cart);
    }

    public function getCart(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        return response()->json($cart ? $cart->items : []);
    }
}
