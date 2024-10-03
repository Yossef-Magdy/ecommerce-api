<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductReview;
use App\Http\Resources\ProductReviewResource;

class ProductReviewsController extends Controller
{
    function __construct()
    {
        $this->modelName = "review";
    }
    public function index()
    {
        $reviews = ProductReview::Paginate(10);
        return ProductReviewResource::collection($reviews);
    }

    public function show(ProductReview $review)
    {
        return ProductReviewResource::make($review);
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return $this->deletedResponse();
    }
}
