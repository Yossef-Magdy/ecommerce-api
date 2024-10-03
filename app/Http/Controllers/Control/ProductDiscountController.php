<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreProductDiscountRequest;
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

    public function show($id)
    {
        return response()->json([
            'message' => 'Discount retrieved successfully',
            'data' => ProductDiscount::find($id),
        ], 200);
    }

    public function store(StoreProductDiscountRequest $request)
    {
        $produc_discount = null;

        // check if discount already exists
        $discount = ProductDiscount::where('product_id', $request['product_id'])->exists();
        if ($discount) {
            return response()->json([
                'message' => 'This product already has a discount',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $produc_discount = ProductDiscount::create($request->all());
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => 'Discount not created',
                'errors' => $errors->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Discount created successfully',
            'data' => $produc_discount,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Find discount
        $discount = ProductDiscount::find($id);

        // Check if discount exists
        if (!$discount) {
            return response()->json([
                'message' => 'Discount not found',
            ], 404);
        }

        // Update discount and return response
        $discount->update($request->all());
        return response()->json([
            'message' => 'Discount updated successfully',
            'data' => $discount,
        ], 200);
    }

    public function destroy($id)
    {
        // Find discount
        $discount = ProductDiscount::find($id);

        // Check if discount exists
        if (!$discount) {
            return response()->json([
                'message' => 'Discount not found',
            ], 404);
        }

        // Delete discount and return response
        $discount->delete();
        return response()->json([
            'message' => 'Discount deleted successfully',
        ], 200);
    }
}
