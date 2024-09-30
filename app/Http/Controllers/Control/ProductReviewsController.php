<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductReview;
use App\Http\Requests\Api\StoreReviewRequest;
use App\Http\Requests\Api\UpdateReviewRequest;
use App\Http\Resources\ProductReviewResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
