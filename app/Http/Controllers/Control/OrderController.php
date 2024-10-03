<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Http\Requests\Control\UpdateOrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderResource::collection(Order::with('orderItems', 'orderCoupon.coupon')->latest()->paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return OrderResource::make($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        if ($request->has('shipping_status')) {
            $order->shipping->update(['status' => $request['shipping_status']]);
        }

        if ($request->has('payment_status')) {
            $order->payment->update(['status' => $request['payment_status']]);
        }

        return $this->updatedResponse(OrderResource::make($order));
    }
}
