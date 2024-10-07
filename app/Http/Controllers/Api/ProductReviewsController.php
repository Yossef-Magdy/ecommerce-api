<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Requests\Api\UpdateReviewRequest;
use App\Http\Requests\ProductReviewsRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\Products\ProductReview;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductReviewsController extends Controller
{
    function __construct()
    {
        $this->modelName = "review";
    }
    public function index(ProductReviewsRequest $request)
    {
        $reviews = ProductReview::all()->where('product_id', $request->product_id);
        return ProductReviewResource::collection($reviews);
    }

    public function show(ProductReview $review)
    {
        return ProductReviewResource::make($review);
    }

    public function store(StoreReviewRequest $request)
    {        
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $review = ProductReview::create($data);
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => "You can only create one review per product",
            ], 500);
        }
        $data = ProductReviewResource::make($review);
        return $this->createdResponse($data);
    }

    public function update(UpdateReviewRequest $request, ProductReview $review)
    {
        $review->update($request->validated());
        $data = ProductReviewResource::make($review);
        return $this->updatedResponse($data);
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return $this->deletedResponse();
    }
}
