<?php

use App\Http\Controllers\Api\ProductDiscountController;
use App\Http\Controllers\Api\ProductReviewsController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

// Routes for products
Route::apiResource('/products', ProductsController::class)->only(['index', 'show']);
Route::apiResource('/reviews', ProductReviewsController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles.permissions');

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'roles' => $user->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return $permission->name;
                    }),
                ];
            }),
            'permissions' => $user->roles->flatMap(function ($role) {
                return $role->permissions->pluck('name');
            })->unique()->values()->all(),
            'hasPermissionEdit' => $user->hasPermission('edit'),
        ];
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin-only routes
    Route::middleware('can:manage-products')->group(function () {
        Route::apiResource('/products', ProductsController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('/discounts', ProductDiscountController::class);
    });

    Route::apiResource('/reviews', ProductReviewsController::class)->only(['store', 'update', 'destroy']);
});
