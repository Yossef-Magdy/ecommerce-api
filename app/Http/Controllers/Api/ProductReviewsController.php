<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Requests\Api\UpdateReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\Products\Product;
use App\Models\Products\ProductReview;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReviewsController extends Controller
{
    public function index(Request $request)
    {
        $reviews = null;
        $product_id = $request['product_id'];
        if ($product_id) {
            $reviews = Product::where('id', $product_id)->first()?->reviews;
        } else {
            $reviews = ProductReview::all();
        }
        
        if (!$reviews) {
            return response()->json([
                'message' => 'Reviews not found',
            ], 404);
        }

        return response()->json([
            'message' => $product_id ? 'Product reviews retrieved successfully' : 'Global Reviews retrieved successfully',
            'data' => ProductReviewResource::collection($reviews),
        ], 200);
    }

    public function show(ProductReview $review)
    {
        return response()->json([
            'message' => 'Product review retrieved successfully',
            'data' => $review,
        ], 200);
    }

    public function store(StoreReviewRequest $request)
    {        
        DB::beginTransaction();
        try {
            $request['user_id'] = Auth::user()->id;
            $review = ProductReview::create($request->all());
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => "You can only create one review per product",
            ], 500);
        }
        return response()->json([
            'message' => 'Product review created successfully',
            'data' => $review,
        ], 201);
    }

    public function update(UpdateReviewRequest $request, ProductReview $review)
    {
        $review->update($request->all());

        return response()->json([
            'message' => 'Product review updated successfully',
            'data' => $review,
        ], 200);
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return response()->json([
            'message' => 'Product review deleted successfully',
        ], 200);
    }
}
