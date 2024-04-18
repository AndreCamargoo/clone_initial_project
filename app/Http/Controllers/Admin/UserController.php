<?php

namespace App\Http\Controllers\Admin;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\Repositories\Contracts\User\UserRepositoryInterface;
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
        $user = $this->repository->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request->filter
        );

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
        $this->repository->store(CreateUserDTO::makeFromRequest($request));
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
        $request['id'] = $id;
        $this->repository->update(UpdateUserDTO::makeFromRequest($request));

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
