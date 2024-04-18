<?php

namespace App\Http\Controllers\Api;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function __construct(protected UserRepositoryInterface $repository)
    {
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

        return UserResource::collection($user);
        //->additional([])
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateUser $request)
    {
        $user = $this->repository->store(CreateUserDTO::makeFromRequest($request));
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->repository->findById($id);
        if (!$user) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateUser $request, string|int $id)
    {
        $request['id'] = $id;
        $user = $this->repository->update(UpdateUserDTO::makeFromRequest($request));
        if (!$user) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $user = $this->repository->delete($id);
        if (!$user) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
