<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProduct;
use App\Repositories\Contracts\ProductRepositoryInterface;
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
    public function index()
    {
        // ELOQUENT
        // $product = $this->repository->orderBy("price", "ASC")->relationships("category")->paginate(10);

        // QUERY BUILDER
        $product = $this->repository->orderBy("price", "DESC")->getAll();
        // $product = $this->repository->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->getAll();

        // $product = $this->repository->orderBy("id", "DESC")->paginate(10);
        // $product = $this->repository
        //     ->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->paginate(10);

        // $product = $this->repository->findWhere("id", 2);   
        // $product = $this->repository->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->findWhere("id", 2);

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
        $this->repository->store([
            "name" => $request->name,
            "url" => $request->url,
            "description" => $request->description,
            "price" => $request->price,
            "category_id" => $request->category_id
        ]);

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
        $this->repository->update($id, [
            "name" => $request->name,
            "url" => $request->url,
            "description" => $request->description,
            "price" => $request->price,
            "category_id" => $request->category_id
        ]);

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
