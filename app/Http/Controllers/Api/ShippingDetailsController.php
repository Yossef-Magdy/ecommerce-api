<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipping\ShippingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingDetailsController extends Controller
{
    function __construct()
    {
        $this->modelName = "shipping detail";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ShippingDetails::all()->where('user_id', Auth::id());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shippingDetail = ShippingDetails::create($request->validate());
        return $this->createdResponse($shippingDetail);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingDetails $shippingDetails)
    {
        return response()->json($shippingDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingDetails $shippingDetails)
    {
        $shippingDetails->update($request->validated());
        return $this->updatedResponse($shippingDetails);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingDetails $shippingDetails)
    {
        $shippingDetails->delete();
        return $this->deletedResponse();
    }
}
