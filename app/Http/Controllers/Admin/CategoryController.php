<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = DB::table("categories")->orderBy("id", "DESC")->paginate(10);
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
        DB::table("categories")->insert([
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
        $categories = DB::table("categories")->where("id", $id)->first();
        if ($categories) return $categories;

        return "Catogoria não encontrada";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = DB::table("categories")->where("id", $id)->first();
        if ($categories) return $categories;

        return "Catogoria não encontrada";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateCategory $request, string $id)
    {
        DB::table("categories")->where("id", $id)->update([
            "title" => $request->title,
            "url" => $request->url,
            "description" => $request->description
        ]);

        return "Categoria atualizado com sucesso!";
    }

    /**
     * Search the specified resource in storage.
     */
    public function search(Request $request)
    {
        $data = $request->except("_token");

        $categories = DB::table("categories")
            ->where(function ($query) use ($data) {
                if (isset($data["title"])) {
                    $query->where('title', $data["title"]);
                }
                if (isset($data["url"])) {
                    $query->where("url", $data["url"]);
                }
                if (isset($data["description"])) {
                    $query->where("description", "LIKE", "%{$data["url"]}%");
                }
            })->orderBy("id", "DESC")->paginate(10);

        return $categories;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = DB::table("categories")->where("id", $id)->first();
        if ($categories) $categories->delete();

        return "Catogoria não encontrada";
    }
}
