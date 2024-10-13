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
use App\Models\Shipping\ShippingDetail;
use Exception;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;

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
            $coupon = $this->validateCoupon($data['coupon'] ?? null);
            $dilevryCharge = ShippingDetail::findOrFail($data['shipping_detail_id'])?->governorate?->fee ?? 50;

            $this->updateProductQuantities($data['items'], $coupon);

            // Create order
            $newOrder = Order::create($data);
            $amount = array_sum(array_column($data['items'], 'total_price')) + $dilevryCharge;

            // Create Stripe charge if payment method is Stripe Method 1
            $charge = null;
            if ($data['payment_method'] === 'stripe') {
                $charge = $this->createStripeCharge($data, $newOrder->id, $amount);
                if ($charge->status !== 'succeeded') {
                    throw new Exception('Payment failed');
                }
            }

            // Make payment intent if payment method is Stripe Method 2
            // $paymentIntent = null;
            // if ($data['payment_method'] === 'stripe') {
            //     $paymentIntent = $this->createPaymentIntent($data, $newOrder->id, $amount);

            //     if ($paymentIntent->status === 'requires_payment_method') {
            //         throw new Exception('Payment requires additional payment method.');
            //     }

            //     if ($paymentIntent->status !== 'succeeded') {
            //         throw new Exception('Payment failed: ' . $paymentIntent);
            //     }
            // }


            // Add coupon usage
            if ($coupon) {
                $newOrder->orderCoupon()->create(['coupon_id' => $coupon->id]);
                $coupon->decrementUsesCount();
            }

            // Create order items and shipping
            $newOrder->shipping()->create(['shipping_detail_id' => $data['shipping_detail_id']]);
            $newOrder->orderItems()->createMany($data['items']);

            // Create payment record

            // Method 1
            $this->createPaymentRecord($newOrder, $data['payment_method'], $amount, $charge ?? null);
            
            // Method 2
            // $this->createPaymentIntentRecord($newOrder, $data['payment_method'], $amount, $paymentIntent ?? null);
            
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => OrderResource::make($newOrder),
                'success' => true,

                // Method 1
                'charge' => $charge ?? null,

                // Method 2
                // 'paymentIntent' => $paymentIntent ?? null,
                
            ]);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage(),
                'success' => false
            ], 400);
        }
    }

    private function validateCoupon($couponCode)
    {
        if (!$couponCode) return null;

        $coupon = Coupon::where('coupon_code', $couponCode)->firstOrFail();

        if ($coupon->isExpired()) {
            throw new Exception('Coupon expired');
        }

        if ($coupon->isUsed()) {
            throw new Exception('Coupon ended use');
        }

        return $coupon;
    }

    private function updateProductQuantities(array &$items, $coupon)
    {
        foreach ($items as &$item) {
            $productDetail = ProductDetail::findOrFail($item['product_detail_id']);

            if ($productDetail->stock < $item['quantity']) {
                throw new Exception('Not enough stock for this product');
            }

            $productDetail->decrement('stock', $item['quantity']);

            $item_discount = $productDetail->product->discount;
            if ($item_discount && !$item_discount->isExpired()) {
                $discount = $this->discountCalculator($item_discount->type, $item_discount->value, $productDetail->price);
                $productDetail->price -= $discount;
                $item['discount'] = $discount;
            }

            if ($coupon) {
                $item['discount'] += $this->discountCalculator($coupon->discount_type, $coupon->discount_value, $productDetail->price);
            }

            $item['total_price'] = $productDetail->price * $item['quantity'];
        }
    }

    private function createStripeCharge($data, $orderId, $amount)
    {
        Stripe::setApiKey(config('stripe.api_key.secret'));

        $shippingDetail = ShippingDetail::findOrFail($data['shipping_detail_id']);
        $amountInCents = $amount * 100;

        return Charge::create([
            'amount' => $amountInCents, // 1 Dollar = 100 Cents
            'currency' => $data['currency'],
            'description' => 'Payment for order #' . $orderId,
            'statement_descriptor' => 'ELEGANT wear',
            'source' => $data['stripeToken'],
            'receipt_email' => Auth::user()->email,
            'metadata' => [
                'order_id' => $orderId,
                'customer_id' => Auth::user()->id,
                'shipping_address' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => $shippingDetail->phone_number,
                    'address' => [
                        'line1' => $shippingDetail->address,
                        'line2' => $shippingDetail->apartment,
                        'city' => $shippingDetail->city,
                        'state' => $shippingDetail->governorate->name,
                        'postal_code' => $shippingDetail->postal_code,
                    ],
                ]),
            ],
        ]);
    }

    private function createPaymentRecord($order, $paymentMethod, $amount, $charge)
    {
        if ($paymentMethod === 'stripe' && $charge) {
            $order->payment()->create([
                'method' => $paymentMethod,
                'paid_amount' => $charge->amount_captured / 100,
                'outstand_amount' => $amount - ($charge->amount_captured / 100),
                'status' => $charge->status
            ]);
        } else {
            $order->payment()->create([
                'method' => $paymentMethod,
                'outstand_amount' => $amount, // add delivery charge
            ]);
        }
    }

    private function createPaymentIntent($data, $orderId, $amount)
    {
        Stripe::setApiKey(config('stripe.api_key.secret'));

        $shippingDetail = ShippingDetail::findOrFail($data['shipping_detail_id']);
        $amountInCents = $amount * 100;

        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents, // 1 Dollar = 100 Cents
            'currency' => $data['currency'],
            'description' => 'Payment for order #' . $orderId,
            'receipt_email' => Auth::user()->email,
            'metadata' => [
                'order_id' => $orderId,
                'customer_id' => Auth::user()->id,
            ],
            'payment_method_data' => [
                'type' => 'card',
                'card' => [
                    'token' => $data['stripeToken'],
                ],
            ],
            'shipping' => [
                'name' => Auth::user()->first_name,
                'phone' => Auth::user()->phone_number,
                'address' => [
                    'line1' => $shippingDetail->address,
                    'line2' => $shippingDetail->apartment,
                    'city' => $shippingDetail->city,
                    'state' => $shippingDetail->governorate->name,
                    'postal_code' => $shippingDetail->postal_code,
                ],
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        if ($paymentIntent->status === 'requires_confirmation') {
            $paymentIntent->confirm();
        }

        return $paymentIntent;
    }

    private function createPaymentIntentRecord($order, $paymentMethod, $amount, $paymentIntent)
    {
        if ($paymentMethod === 'stripe' && $paymentIntent) {
            $order->payment()->create([
                'method' => $paymentMethod,
                'paid_amount' => $paymentIntent->amount_received / 100,
                'outstand_amount' => $amount - ($paymentIntent->amount_received / 100),
                'status' => $paymentIntent->status,
            ]);
        } else {
            $order->payment()->create([
                'method' => $paymentMethod,
                'outstand_amount' => $amount,
            ]);
        }
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
}
