<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUser;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Response;

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

        return UserResource::collection($user);
        //->additional([])
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateUser $request)
    {
        $user = $this->repository->store([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

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
    public function update(StoreUpdateUser $request, string $id)
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

        $user = $this->repository->update($id, $data);

        if (!$user) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->repository->findById($id);
        if (!$user) return response()->json(['error' => 'Not found'], Response::HTTP_NOT_FOUND);

        $user->delete($user->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
