<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreSubcategoryRequest;
use App\Http\Requests\Control\UpdateSubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Models\Categories\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    function __construct() {
        $this->authorizeResource(Subcategory::class, 'subcategory');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SubcategoryResource::collection(Subcategory::with('category')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryRequest $request)
    {
        Subcategory::create($request->validated());
        return response()->json(['message' => 'subcategory added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return new SubcategoryResource($subcategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory->update($request->validated());
        return response()->json(['message' => 'subcategory updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return response()->json(['message' => 'category deleted successfully']);
    }
}
