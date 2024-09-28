<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductResource;
use App\Models\Products\Attribute;
use App\Models\Products\Product;
use App\Models\Products\ProductImage;
use App\Models\Products\ProductOption;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public User $user;

    function __construct() {
        $this->user = Auth::user();
    }

    public function index(Request $request)
    {
        $products = null;
        if (!$request['count'] && !$request['page']) {
            $products = ProductResource::collection(Product::all());
        } else {
            $products = Product::paginate($request['count'] ?? 1);
            $products->data = ProductResource::collection($products);
        }
        return response()->json($products, 200);
    }

    public function show(Product $product)
    {
        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => ProductDetailsResource::make($product),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $coverPath = $this->uploadCoverImage($request);

            $product = Product::create(array_merge($request->all(), [
                'cover_image' => $coverPath,
            ]));

            $this->saveProductOptions(json_decode($request->input('attributes'), true), $product->id);
            $this->uploadProductImages($request, $product->id);

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

            if ($request->hasFile('cover_image')) {
                Storage::delete($product->cover_image);
                $product->cover_image = $request->file('cover_image')->store('', 'product_cover');
            }

            $product->update($request->except(['cover_image', 'product_images', 'attributes']));

            if ($request->hasFile('product_images')) {
                if ($product->images) {
                    foreach ($product->images as $image) {
                        Storage::disk('product_images')->delete($image->image_url);
                    }
                }
                $this->uploadProductImages($request, $product->id);
            }

            if ($request->has('attributes')) {
                $this->updateProductOptions(json_decode($request->input('attributes'), true), $product->id);
            }

            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        if (!Auth::user()->isAdmin() && Auth::user()->cannot('delete', $product)) {
            return response()->json(['message' => 'forbidden'], 403);
        }
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
        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
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

    private function saveProductOptions($attributes, $productId)
    {
        foreach ($attributes as $attributeData) {
            $name = $attributeData['name'];
            $options = $attributeData['options'];

            $attribute = Attribute::firstOrCreate(['name' => $name]);
            foreach ($options as $optionValue) {
                $option = $attribute->options()->firstOrCreate(['value' => $optionValue]);
                ProductOption::create([
                    'product_id' => $productId,
                    'attribute_option_id' => $option->id,
                ]);
            }
        }
    }


    private function updateProductOptions($attributes, $productId)
    {
        ProductOption::where('product_id', $productId)->delete();

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::firstOrCreate(['name' => $attributeData['name']]);

            foreach ($attributeData['options'] as $optionValue) {
                $option = $attribute->options()->firstOrCreate(['value' => $optionValue]);

                ProductOption::create([
                    'product_id' => $productId,
                    'attribute_option_id' => $option->id,
                    'price' => $attributeData['name'] === 'price' ? $optionValue : null,
                    'stock' => $attributeData['name'] === 'stock' ? $optionValue : null,
                ]);
            }
        }
    }
}
