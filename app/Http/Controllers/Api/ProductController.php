<?php

namespace App\Http\Controllers\Api;

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
    public function index()
    {
        $products = Product::paginate(10);
        $productsCollection = ProductResource::collection($products);

        return $productsCollection;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $productFields = $request->only('name', 'cost', 'price', 'unit', 'description', 'feature_image');
        $productFields['slug'] = str_slug($productFields['name']);

        $product = Product::create($productFields);
        $productResource = new ProductResource($product);

        return response()->json($productResource, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
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

        $product->update($productFields);
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
