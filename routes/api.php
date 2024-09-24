<?php

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

// Routes for viewing products
Route::apiResource('/products', ProductsController::class); //->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin-only routes
    // Route::middleware('can:manage-products')->group(function () {
    //     Route::post('/products', [ProductsController::class, 'store']);
    //     Route::put('/products/{product}', [ProductsController::class, 'update']);
    //     Route::delete('/products/{product}', [ProductsController::class, 'destroy']);
    // });
});