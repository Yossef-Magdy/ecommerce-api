<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Products\ProductDetail;
use Illuminate\Http\Request;
use App\Http\Requests\Control\StoreProductDetailRequest;
use App\Http\Requests\Control\UpdateProductDetailRequest;
use App\Http\Resources\ProductDetailResource;

class ProductDetailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductDetailRequest $request)
    {
        $data = $request->validated();
        $productDetail = ProductDetail::create($data);
        return $this->createdResponse(ProductDetailResource::make($productDetail));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductDetailRequest $request, ProductDetail $productDetail)
    {
        $data = $request->validated();
        $productNewDetail = $productDetail->update($data);
        return $this->updatedResponse($productNewDetail);
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
