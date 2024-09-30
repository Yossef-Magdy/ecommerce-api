<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreSubcategoryRequest;
use App\Http\Requests\Control\UpdateSubcategoryRequest;
use App\Models\Categories\Subcategory;

class SubcategoryController extends Controller
{
    function __construct()
    {
        $this->modelName = "subcategory";
        $this->authorizeResource(Subcategory::class, 'subcategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryRequest $request)
    {
        Subcategory::create($request->validated());
        return $this->createdResponse();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory->update($request->validated());
        return $this->updatedResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return $this->deletedResponse();
    }
}
