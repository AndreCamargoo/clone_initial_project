<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = $this->product->with('category')->paginate(10);
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
        $this->product->create([
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
     */
    public function show(Product $product)
    {
        if ($product) return $product->with('category')->first();
        return "Produto não encontrado";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        if ($product) return $product;
        return "Produto não encontrado";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateProduct $request, Product $product)
    {
        $product->update([
            "name" => $request->name,
            "url" => $request->url,
            "description" => $request->description,
            "price" => $request->price,
            "category_id" => $request->category_id
        ]);

        return "Produto atualizado com sucesso!";
    }

    /**
     * Search the specified resource in storage.
     */
    public function search(Request $request)
    {
        $products = $this->product->with(["category"])
            ->where(function ($query) use ($request) {
                if ($request->name) {
                    $filter = $request->name;

                    $query->where(function ($querySub) use ($filter) {
                        $querySub->where("name", $filter)
                            ->orWhere("description", "LIKE", "%{$filter}%");
                    });
                }

                if ($request->price) {
                    $query->where("price", ">=", $request->price);
                }

                if ($request->category) {
                    $query->orWhere("category_id", $request->category);
                }
            })->paginate(10);
        // ->toSql();

        return $products;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product) $product->delete();

        return "Produto não encontrado";
    }
}
