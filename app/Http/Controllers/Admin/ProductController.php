<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProduct;
use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ELOQUENT
        $product = $this->repository->orderBy("price", "ASC")->relationships("category")->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request->filter
        );

        return $product;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return "Criar a visão de cadastro";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateProduct $request)
    {
        $this->repository->store(CreateProductDTO::makeFromRequest($request));

        return "Produto cadastrado!";
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     */
    public function show($id)
    {
        $product = $this->repository->findWhereFirst('id', $id);
        if ($product) return $product;

        return "Produto não encontrado";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int|string $id
     */
    public function edit($id)
    {
        $product = $this->repository->findById($id);
        if ($product) return $product;

        return "Produto não encontrado";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int|string $id
     */
    public function update(StoreUpdateProduct $request, $id)
    {
        $request["id"] = $id;
        $this->repository->update(UpdateProductDTO::makeFromRequest($request));

        return "Produto atualizado com sucesso!";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int|string $id
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return "Produto não deletado";
    }

    /**
     * Search the specified resource in storage.
     */
    public function search(Request $request)
    {
        $products = $this->repository->search($request);

        return $products;
    }
}
