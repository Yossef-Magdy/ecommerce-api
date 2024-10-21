<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreProductDiscountRequest;
use App\Http\Requests\Control\UpdateProductDiscountRequest;
use App\Http\Resources\Control\DiscountResource;
use App\Models\Products\ProductDiscount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductDiscountController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Discounts retrieved successfully',
            'data' => ProductDiscount::all()->load('product'),
        ], 200);
    }

    public function show(ProductDiscount $discount)
    {
        return DiscountResource::make($discount);
    }

    public function store(StoreProductDiscountRequest $request)
    {
        $product_discount = null;
        $discount = ProductDiscount::where('product_id', $request['product_id'])->exists();
        if ($discount) {
            return response()->json([
                'message' => 'This product already has a discount',
            ], 422);
        }
        DB::beginTransaction();
        try {
            $product_discount = ProductDiscount::create($request->validated());
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => 'Discount not created',
                'errors' => $errors->getMessage(),
            ], 422);
        }
        return $this->createdResponse(DiscountResource::make($product_discount->refresh()));
    }

    public function update(UpdateProductDiscountRequest $request, ProductDiscount $discount)
    {
        $discount->update($request->validated());
        return $this->updatedResponse(DiscountResource::make($discount->refresh()));
    }

    public function destroy(ProductDiscount $discount)
    {
        $discount->delete();
        return $this->deletedResponse();
    }
}
