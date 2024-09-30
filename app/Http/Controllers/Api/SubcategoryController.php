<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubcategoryResource;
use App\Models\Categories\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SubcategoryResource::collection(Subcategory::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
    {
        return SubcategoryResource::make($subcategory);
    }
}