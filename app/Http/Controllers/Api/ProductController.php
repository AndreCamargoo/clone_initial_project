<?php

namespace App\Http\Controllers\Api;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProduct;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product = $this->repository->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request->filter
        );

        return ProductResource::collection($product);
        //->additional([])
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateProduct $request)
    {
        $product = $this->repository->store(CreateProductDTO::makeFromRequest($request));
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->repository->findById($id);
        if (!$product) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateProduct $request, string $id)
    {
        $request['id'] = $id;
        $product = $this->repository->update(UpdateProductDTO::makeFromRequest($request));
        if (!$product) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $product = $this->repository->delete($id);
        if (!$product) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
