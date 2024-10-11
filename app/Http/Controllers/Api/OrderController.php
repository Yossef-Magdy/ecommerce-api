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

use Stripe\Stripe;
use Stripe\Charge;

class OrderController extends Controller
{
    function __construct()
    {
        $this->modelName = "order";
    }
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
                        'message' => 'Not enough stock for this product'
                    ], 400);
                }

                $productDetail->update(['stock' => $productDetail->stock - $item['quantity']]);

                $item_discount = $productDetail->product->discount;
                if ($item_discount && !$item_discount->isExpired()) {
                    $discount = $this->discountCalculator($item_discount->type, $item_discount->value, $productDetail->price);
                    $productDetail->price -= $discount;
                    $item['discount'] = $discount;
                }

                if ($coupon) {
                    $productDetail->price -= $this->discountCalculator($coupon->discount_type, $coupon->discount_value, $productDetail->price);
                }

                $item['total_price'] = $productDetail->price * $item['quantity'];
            }

            // Stripe Keys
            // STRIPE_SECRET_KEY=sk_test_51Q8SaRAYDkqV8OSb7fqS6WHUrsDT2vGmQIG3O4NDUnVuGPFuPNLW2qv3CdcyXRenEguzK4EQHzt9x8mBbUNrB9gc00UHuyenxB
            // STRIPE_PUBLISHABLE_KEY=pk_test_51Q8SaRAYDkqV8OSb7CBmUOUII185BHQ98c7m36pxUrm8S6KZCbThC7oukcr2ihfQIzpLq1btA19H4Si0EvgFIMqK00Jif1q77f
            
            // create stripe charge
            Stripe::setApiKey(config('stripe.api_key.secret'));
            $amount = array_sum(array_column($data['items'], 'total_price'));
            $charge = Charge::create([
                'amount' => $amount * 100,
                'currency' => $data['currency'],
                'source' => $data['stripeToken'],
                'description' => 'Payment for order',
                'receipt_email' => Auth::user()->email,
            ]);

            // Check if charge is successful
            if ($charge->status !== 'succeeded') {
                throw new Exception('Payment failed');
            }

            // create order
            $newOrder = Order::create($data);

            // add coupon usage
            if ($coupon) {
                $newOrder->orderCoupon()->create(['coupon_id' => $coupon->id]);
                $coupon->decrementUsesCount();
            }

            // create order items
            $newOrder->shipping()->create(['shipping_detail_id' => $data['shipping_detail_id']]);
            $newOrder->payment()->create([
                'method' => $data['payment_method'],
                'paid_amount' => $charge->amount_captured / 100,
                'outstand_amount' => $amount - ($charge->amount_captured / 100),
                'status' => $charge->status
            ]);

            $newOrder->orderItems()->createMany($data['items']);

            // save order
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => OrderResource::make($newOrder),
                'charge' => $charge,
                'success' => true
            ]);
            // return $this->createdResponse(OrderResource::make($newOrder));
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage(),
                'success' => false
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
                return $value;
            case 'percentage':
                return ($price * $value) / 100;
            default:
                return 0;
        }
    }
}
