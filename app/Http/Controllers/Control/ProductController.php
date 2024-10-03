<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreProductRequest;
use App\Http\Requests\Control\UpdateProductRequest;
use App\Http\Resources\ProductDetailsResource;
use App\Models\Products\Product;
use App\Models\Products\ProductImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    function __construct() {
        $this->authorizeResource(Product::class, 'product');
    }
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $coverPath = $this->uploadCoverImage($request);

            $product = Product::create(array_merge($request->all(), [
                'cover_image' => $coverPath,
                'slug' => Str::slug($request['name'], '-'),
            ]));

            $this->uploadProductImages($request, $product->id);

            // Add product categories and subcategories
            $product->categories()->attach($request['categories']);
            $product->subcategories()->attach($request['subcategories']);

            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('cover_image')) {
                Storage::delete($product->cover_image);
                $product->cover_image = $request->file('cover_image')->store('', 'product_cover');
            }

            $product->update($request->except(['cover_image', 'product_images']));
            
            // Edit product categories and subcategories
            $product->categories()->sync($request['categories']);
            $product->subcategories()->sync($request['subcategories']);

            if ($request->hasFile('product_images')) {
                if ($product->images) {
                    foreach ($product->images as $image) {
                        Storage::disk('product_images')->delete($image->image_url);
                    }
                }
                $this->uploadProductImages($request, $product->id);
            }

            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            if ($product->images) {
                foreach ($product->images as $image) {
                    Storage::disk('product_images')->delete($image->image_url);
                }
            }
            if ($product->cover_image !== 'default.png') {
                Storage::disk('product_cover')->delete($product->cover_image);
            }
            $product->delete();
            DB::commit();
        } catch (Exception $errors) {
            DB::rollBack();
            return response()->json([
                'message' => $errors->getMessage()
            ], 500);
        }
        return $this->deletedResponse();
    }


    private function uploadCoverImage($request)
    {
        if ($request->hasFile('cover_image')) {
            return $request->file('cover_image')->store('', 'product_cover');
        }
        return 'default.png';
    }

    private function uploadProductImages($request, $productId)
    {
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $imagePath = $image->store('', 'product_images');
                ProductImage::create([
                    'product_id' => $productId,
                    'image_url' => $imagePath,
                ]);
            }
        }
    }
}