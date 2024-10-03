<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\Products\ProductDetail;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrderResource::collection(Order::with('orderItems', 'orderCoupon.coupon')->where('user_id', Auth::id())->latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $coupon = null;

            // add coupon usage
            if (isset($data['coupon'])) {
                // check coupon status
                $coupon = Coupon::where('coupon_code', $data['coupon'])->first();
                if ($coupon->isExpired()) {
                    return response()->json([
                        'message' => 'Coupon expired'
                    ], 400);
                }

                if ($coupon->isUsed()) {
                    return response()->json([
                        'message' => 'Coupon ended use'
                    ], 400);
                }
            }

            // update quantities
            foreach ($data['items'] as &$item) {
                $productDetail = ProductDetail::where('id', $item['product_detail_id'])->first();
                
                if ($productDetail->stock < $item['quantity']) {
                    return response()->json([
                        'message' => 'Not enough stock for this product' . $productDetail
                    ], 400);
                }
                
                $productDetail->update(['stock' => $productDetail->stock - $item['quantity']]);

                if ($productDetail->product->discount_value) {
                    $productDetail->price = $this->discountCalculator($productDetail->product->discount_type, $productDetail->product->discount_value, $productDetail->price);
                }

                if ($coupon) {
                    $productDetail->price = $this->discountCalculator($coupon->discount_type, $coupon->discount_value, $productDetail->price);
                }

                $item['total_price'] = $productDetail->price * $item['quantity'];
            }

            // create order
            $newOrder = Order::create($data);

            // add coupon usage
            $newOrder->orderCoupon()->create(['coupon_id' => $coupon->id]);
            $coupon->decrementUsesCount();

            // create order items
            $newOrder->shipping()->create($data['shipping']);
            $newOrder->payment()->create($data['payment']);
            $newOrder->orderItems()->createMany($data['items']);

            // save order
            DB::commit();

            return $this->createdResponse(OrderResource::make($newOrder));
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
        return OrderResource::make($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $newOrder = $order->update($request->validated());
        return $this->updatedResponse($newOrder);
    }

    private function discountCalculator($type, $value, $price): float
    {
        switch ($type) {
            case 'fixed':
                return $price - $value;
            case 'percentage':
                return $price - ($price * $value) / 100;
        }
    }
}
