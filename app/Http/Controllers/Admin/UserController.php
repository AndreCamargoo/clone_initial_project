<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ELOQUENT
        // $user = $this->repository->orderBy("id", "ASC")->paginate(10);
        $user = $this->repository->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request->filter
        );

        // QUERY BUILDER
        // $user = $this->repository->orderBy("id", "DESC")->getAll();
        // $user = $this->repository->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->getAll();

        // $user = $this->repository->orderBy("id", "DESC")->paginate(10);
        // $user = $this->repository
        //     ->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->paginate(10);

        // $user = $this->repository->findWhere("id", 2);
        // $user = $this->repository->relationships(["categories;id;category_id;left join"], ["name", "price", "description"], ["title", "url", "description"])
        //     ->orderBy("id", "DESC")
        //     ->findWhere("id", 2);

        return $user;
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
    public function store(StoreUpdateUser $request)
    {
        $this->repository->store([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return "Usuário cadastrado!";
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     */
    public function show($id)
    {
        $user = $this->repository->findWhereFirst('id', $id);
        if ($user) return $user;

        return "Usuário não encontrado";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int|string $id
     */
    public function edit($id)
    {
        $user = $this->repository->findById($id);
        if ($user) return $user;

        return "Usuário não encontrado";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int|string $id
     */
    public function update(StoreUpdateUser $request, $id)
    {
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password
        ];

        if ($request->password) {
            $data["password"] = bcrypt($request->password);
        } else {
            unset($data["password"]);
        }

        $this->repository->update($id, $data);

        return "Usuário atualizado com sucesso!";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int|string $id
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return "Usuário não deletado";
    }

    /**
     * Search the specified resource in storage.
     */
    public function search(Request $request)
    {
        $users = $this->repository->search($request);

        return $users;
    }
}
