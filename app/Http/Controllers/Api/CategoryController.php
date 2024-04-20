<?php

namespace App\Http\Controllers\Api;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\UpdateCategoryDTO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Http\Resources\CategoryResource;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(protected CategoryRepositoryInterface $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $this->repository->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request
        );

        return $category;
        // return CategoryResource::collection($category)->additional([]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateCategory $request)
    {
        $category = $this->repository->store(CreateCategoryDTO::makeFromRequest($request));
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->repository->findById($id);
        if (!$category) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCategory $request, string $id)
    {
        $request['id'] = $id;
        $category = $this->repository->update(UpdateCategoryDTO::makeFromRequest($request));
        if (!$category) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->repository->delete($id);
        if (!$category) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
