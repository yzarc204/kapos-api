<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Http\Resources\Api\ProductCollection;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ProductFilter();
        $filterQuery = $filter->getFilterQuery($request);
        $orderByQuery = $filter->getOrderByQuery($request);

        $products = Product::where($filterQuery)->orderBy($orderByQuery[0], $orderByQuery[1])->paginate();
        if ($request->has('includeCategories')) {
            $products->loadMissing('categories');
        }

        $productsCollection = new ProductCollection($products->withQueryString());
        return response()->json($productsCollection, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $productFields = $request->only('name', 'cost', 'price', 'unit', 'description', 'feature_image', 'categories');
        $productFields['slug'] = str_slug($productFields['name']);

        $product = Product::create($productFields);
        if ($request->has('categories')) {
            $categories = $request->input('categories');
            $product->categories()->sync($categories);
        }
        $product->loadMissing('categories');

        $productResource = new ProductResource($product);
        return response()->json($productResource, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->loadMissing('categories');
        $productResource = new ProductResource($product);

        return response()->json($productResource, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $productFields = $request->only('name', 'cost', 'price', 'unit', 'description', 'feature_image');
        if (isset($productFields['name'])) {
            $productFields['slug'] = str_slug($productFields['name']);
        }

        if ($request->has('categories')) {
            $categories = $request->input('categories');
            $product->categories()->sync($categories);
        }

        $product->update($productFields);
        $product->loadMissing('categories');
        $productResource = new ProductResource($product);

        return response()->json($productResource, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->deleteOrFail();

        $productResource = new ProductResource($product);
        return $productResource;
    }
}
