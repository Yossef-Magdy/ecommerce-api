<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipping\ShippingDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\StoreShippingDetailRequest;
use App\Http\Requests\Api\UpdateShippingDetailRequest;
use App\Http\Resources\ShippingDetailResource;

class ShippingDetailsController extends Controller
{
    function __construct()
    {
        $this->modelName = "shipping detail";
        $this->authorizeResource(ShippingDetail::class, 'shipping_detail');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShippingDetailResource::collection(ShippingDetail::all()->where('user_id', Auth::id()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingDetailRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($data['is_default'] == true) {
            ShippingDetail::query()->where('user_id', $data['user_id'])->update(['is_default' => false]);
        }
        $shippingDetail = ShippingDetail::create($data);
        return $this->createdResponse(ShippingDetailResource::make($shippingDetail));
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingDetail $shippingDetail)
    {
        return ShippingDetailResource::make($shippingDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingDetailRequest $request, ShippingDetail $shippingDetail)
    {
        $shippingDetail->update($request->validated());
        return $this->updatedResponse(ShippingDetailResource::make($shippingDetail));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingDetail $shippingDetail)
    {
        $shippingDetail->delete();
        return $this->deletedResponse();
    }
}
