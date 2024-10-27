<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductDetail;
use App\Models\Products\Product;
use App\Http\Requests\Control\StoreProductDetailRequest;
use App\Http\Requests\Control\UpdateProductDetailRequest;
use App\Http\Resources\ProductDetailResource;

class ProductDetailController extends Controller
{
    function __construct()
    {
        $this->modelName = "detail";
        $this->authorizeResource(Product::class, 'product');
    }

    public function store(StoreProductDetailRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['price'])) {
            $data['price'] = Product::find($data['product_id'])->price;
        }
        $productDetail = ProductDetail::create($data);
        return $this->createdResponse(ProductDetailResource::make($productDetail));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductDetailRequest $request, ProductDetail $productDetail)
    {
        $productDetail->update($request->validated());
        return $this->updatedResponse($productDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductDetail $productDetail)
    {
        $productDetail->delete();
        return $this->deletedResponse();
    }
}
