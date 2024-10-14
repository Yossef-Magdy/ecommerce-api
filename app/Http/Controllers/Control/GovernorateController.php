<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreGovernorateRequest;
use App\Http\Requests\Control\UpdateGovernorateRequest;
use App\Http\Resources\GovernorateResource;
use App\Models\Governorate;

class GovernorateController extends Controller
{
    function __construct()
    {
        $this->modelName = "governorate";
        $this->authorizeResource(Governorate::class, 'governorate');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGovernorateRequest $request)
    {
        Governorate::create($request->validated());
        return $this->createdResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGovernorateRequest $request, Governorate $governorate)
    {
        $governorate->update($request->validated());
        return $this->updatedResponse(GovernorateResource::make($governorate));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return $this->deletedResponse();
    }
}
