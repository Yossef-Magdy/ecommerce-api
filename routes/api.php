<?php

use App\Http\Controllers\Api\BestSellerController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\ProductReviewsController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\ProductDetailController;
use App\Http\Controllers\Api\ShippingDetailsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Control\ProductReviewsController as ControlProductReviewsController;
use App\Http\Controllers\Control\CouponController as ControlCouponController;
use App\Http\Controllers\Control\GovernorateController as ControlGovernorateController;
use App\Http\Controllers\Control\UserController;
use App\Http\Controllers\Control\CategoryController as ControlCategoryController;
use App\Http\Controllers\Control\SubcategoryController as ControlSubcategoryController;
use App\Http\Controllers\Control\OrderController as ControlOrderController;
use App\Http\Controllers\Control\ProductController as ControlProductController;
use App\Http\Controllers\Control\ProductDiscountController;
use App\Http\Controllers\Control\ProductDetailController as ControlProductDetailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Control\AnalyticsController;
use App\Http\Controllers\Control\PermissionController;
use App\Http\Controllers\Control\RoleController;
use App\Http\Middleware\ConvertToIntegerArray;

use App\Http\Controllers\Payment\StripeController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/callback/google', [AuthController::class, 'google']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

Route::apiResource('/products', ProductController::class)->only(['index', 'show']);
Route::apiResource('/product-details', ProductDetailController::class)->only(['index', 'show']);
Route::apiResource('/reviews', ProductReviewsController::class)->only(['index', 'show']);
Route::apiResource('/categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('/subcategories', SubcategoryController::class)->only(['index', 'show']);
Route::apiResource('/coupons', CouponController::class)->only(['show']);
Route::apiResource('/governorates', GovernorateController::class)->only(['index', 'show']);
Route::get('/search', [SearchController::class, 'index']);
Route::get('/best-seller', [BestSellerController::class, 'index']);
Route::get('/collections/{category}', [CollectionController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('control')->group(function () {
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/products', ControlProductController::class)->middleware(ConvertToIntegerArray::class);
        Route::apiResource('/product-details', ControlProductDetailController::class)->except(['index', 'show']);
        Route::apiResource('/discounts', ProductDiscountController::class);
        Route::apiResource('/categories', ControlCategoryController::class)->except(['show']);
        Route::apiResource('/coupons', ControlCouponController::class)->except(['show']);
        Route::apiResource('/subcategories', ControlSubcategoryController::class)->except(['show']);
        Route::apiResource('/orders', ControlOrderController::class)->only(['index', 'show', 'update']);
        Route::apiResource('/governorates', ControlGovernorateController::class)->except(['show']);
        Route::apiResource('/reviews', ControlProductReviewsController::class)->only(['view', 'show', 'destroy']);
        Route::apiResource('/analytics', AnalyticsController::class)->only(['index', 'show']);
        Route::apiResource('/roles', RoleController::class);
        Route::apiResource('/permissions', PermissionController::class)->only(['index', 'show']);
    });

    Route::apiResource('/reviews', ProductReviewsController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('/shipping-details', ShippingDetailsController::class);
    Route::apiResource('/orders', OrderController::class)->only(['index', 'store','show', 'update']);

    Route::post('/charge', [StripeController::class, 'charge']);
});