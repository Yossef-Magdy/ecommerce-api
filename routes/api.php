<?php

use App\Http\Controllers\Control\UserController;
use App\Http\Controllers\Control\CategoryController;
use App\Http\Controllers\Control\SubcategoryController;
use App\Http\Controllers\Control\OrderController;
use App\Http\Controllers\Api\ProductReviewsController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ProductDiscountController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

Route::apiResource('/products', ProductsController::class)->only(['index', 'show']);
Route::apiResource('/reviews', ProductReviewsController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('control')->group(function () {
        Route::apiResource('/users', UserController::class);
        Route::apiResource('/products', ProductsController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('/discounts', ProductDiscountController::class);
        Route::apiResource('/categories', CategoryController::class);
        Route::apiResource('/subcategories', SubcategoryController::class);
        Route::apiResource('/orders', OrderController::class)->only(['index', 'show', 'update']);
    });

    Route::apiResource('/reviews', ProductReviewsController::class)->only(['store', 'update', 'destroy']);
});
