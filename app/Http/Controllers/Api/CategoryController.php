<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ProductCollection;
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

        return response()->json($categoriesCollection, Response::HTTP_OK);
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
    public function show(Request $request, Category $category)
    {
        $categoryResource = new CategoryResource($category);

        return response()->json($categoryResource, Response::HTTP_OK);
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

    public function products(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $filter = new ProductFilter();
        $filterQuery = $filter->getFilterQuery($request);
        $orderByQuery = $filter->getOrderByQuery($request);

        $products = $category->products()->where($filterQuery)->orderBy($orderByQuery[0], $orderByQuery[1])->paginate();

        $productCollection = new ProductCollection($products->withQueryString());
        return response()->json($productCollection, Response::HTTP_OK);
    }
}
