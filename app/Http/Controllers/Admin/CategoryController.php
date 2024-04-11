<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ELOQUENT
        // $categories = $this->repository->orderBy("title", "ASC")->relationships("products")->paginate(10);

        // QUERY BUILDER
        $categories = $this->repository->orderBy("id", "DESC")->getAll();
        // $categories = $this->repository->relationships(["products;category_id;id;left join"], ["title", "url", "description"], ["name", "price", "description"])
        //     ->orderBy("id", "DESC")
        //     ->getAll();

        // $categories = $this->repository->orderBy("id", "DESC")->paginate(10);
        // $categories = $this->repository
        //     ->relationships(["products;category_id;id;left join"], ["title", "url", "description"], ["name", "price", "description"])
        //     ->orderBy("id", "DESC")
        //     ->paginate(10);

        // $categories = $this->repository->findWhere("id", 2);
        // $categories = $this->repository->relationships(["products;category_id;id;left join"], ["title", "url", "description"], ["name", "price", "description"])
        //     ->orderBy("id", "DESC")
        //     ->findWhere("id", 2);

        return $categories;
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
    public function store(StoreUpdateCategory $request)
    {
        $this->repository->store([
            "title" => $request->title,
            "url" => $request->url,
            "description" => $request->description
        ]);

        return "Categoria cadastrada!";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = $this->repository->findById($id);
        // $categories = $this->repository
        //     ->relationships(["products;category_id;id;left join"], ["id", "title", "url", "description"], ["name", "price", "description"])
        //     ->findById($id);

        if ($categories) return $categories;

        return "Catogoria não encontrada";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = $this->repository->findWhereFirst("id", $id);
        if ($categories) return $categories;

        return "Catogoria não encontrada";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCategory $request, string $id)
    {
        $this->repository->update($id, [
            "title" => $request->title,
            "url" => $request->url,
            "description" => $request->description
        ]);

        return "Categoria atualizado com sucesso!";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Opcional, uma vez que o banco de dados está habilitado o onDelete 'cascade'
        if (count($this->repository->productsByCategoryId($id)) > 0) {
            return "Oops parece que tem produtos vinculados a essa categoria, deseja realmente deletar?";
        }

        $this->repository->delete($id);

        return "Catogoria não deletada";
    }

    /**
     * Search the specified resource in storage.
     */
    public function search(Request $request)
    {
        $data = $request->except("_token");
        $categories = $this->repository->search($data);

        return $categories;
    }
}
