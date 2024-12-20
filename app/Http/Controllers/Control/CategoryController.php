<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Categories\Category;
use App\Http\Requests\Control\StoreCategoryRequest;
use App\Http\Requests\Control\UpdateCategoryRequest;
use App\Http\Resources\Control\CategoryResource as ControlCategoryResource;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->modelName = "category";
        $this->authorizeResource(Category::class, 'category');
    }

    public function index() {
        return ControlCategoryResource::collection(Category::with('subcategories')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return $this->createdResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return $this->updatedResponse(ControlCategoryResource::make($category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->deletedResponse();
    }
}
