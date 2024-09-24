<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductResource;
use App\Models\Products\Product;
use App\Models\Products\ProductImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        if (!$products) {
            return response()->json([
                'message' => 'No products found',
            ], 404);
        }
        return response()->json(ProductResource::collection($products), 200);
    }

    public function show(Product $product)
    {
        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }
        return response()->json([
            'message' => 'Product retrieved successfully',
            'product' => ProductDetailsResource::make($product),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $coverPath = 'default.png';

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('', 'product_cover');
        }

        $product = Product::create(array_merge($request->all(), [
            'cover_image' => $coverPath,
        ]));

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $imagePath = $image->store('', 'product_images');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }


    public function update(UpdateProductRequest $request, Product $product)
    {
        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image !== 'default.png') {
                // delete old image
                Storage::disk('product_cover')->delete($product->cover_image);
            }
            $path = $request->file('cover_image')->store('', 'product_cover');
            $product->cover_image = $path;
        }

        if ($request->hasFile('product_images')) {
            $new_images = $request->file('product_images');
            $stored_images = $product->images()->pluck('image_url')->toArray();
            $new_image_paths = [];

            foreach ($new_images as $image) {
                $image_path = $image->store("", 'product_images');
                $new_image_paths[] = $image_path;

                if (!in_array($image_path, $stored_images)) {
                    ProductImage::create([
                        'image_url' => $image_path,
                        'product_id' => $product->id,
                    ]);
                }
            }

            foreach ($stored_images as $stored_image) {
                if (!in_array($stored_image, $new_image_paths)) {
                    ProductImage::where('image', $stored_image)->where('product_id', $product->id)->delete();
                    Storage::disk('product_images')->delete($stored_image);
                }
            }
        }

        $product->update($request->except('cover_image'));
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => ProductDetailsResource::make($product),
        ]);
    }

    public function destroy(Product $product)
    {
        // Start a new database transaction
        DB::beginTransaction();

        try {
            // Delete the product images
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('product_images')->delete($image->image_url);
                }
            }
            if ($product->cover_image !== 'default.png') {
                Storage::disk('product_cover')->delete($product->cover_image);
            }

            // Delete the product listing
            $product->delete();

            // Commit the transaction
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => $errors->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
