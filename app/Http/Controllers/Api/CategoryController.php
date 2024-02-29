<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate();
        $categoriesCollection = CategoryResource::collection($categories);

        return $categoriesCollection;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $categoryFields = $request->only('name');
        $categoryFields['slug'] = str_slug($categoryFields['name']);

        $category = Category::create($categoryFields);
        $categoryResource = new CategoryResource($category);

        return response()->json($categoryResource, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $categoryFields = $request->only('name');
        if (isset($categoryFields['name'])) {
            $categoryFields['slug'] = str_slug($categoryFields['name']);
        }

        $category->update($categoryFields);
        $categoryResource = new CategoryResource($category);

        return $categoryResource;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->deleteOrFail();

        $categoryResource = new CategoryResource($category);

        return $categoryResource;
    }
}
