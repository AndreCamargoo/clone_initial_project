<?php

namespace App\Services\User;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\Repositories\Contracts\User\RepositoryUserInterface;
use Illuminate\Support\Facades\DB;
use App\Repositories\Exceptions\NotEntityDefined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserBaseEloquentRespository implements RepositoryUserInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function getAll(): object|null
    {
        return $this->entity->all();
    }

    public function findById(string|int $id): object|null
    {
        return $this->entity->find($id);
    }

    public function findWhere(string $column, string $value): object|null
    {
        return $this->entity->where($column, $value)->get();
    }

    public function findWhereFirst(string $column, string $value): object|null
    {
        return $this->entity->where($column, $value)->first();
    }

    public function paginate(int $page = 1, int $totalPerPage = 15, Request $filter = null): object|null
    {
        $result = $this->entity;

        if ($filter["name"] || $filter["email"]) {
            $result = $result->where(function ($query) use ($filter) {
                if ($filter["name"] || $filter["email"]) {
                    $query->where("name", "LIKE", "%" . $filter["name"] . "%");
                    // $query->orWhere('last_name', $filter["name"]);
                }

                if ($filter["email"]) {
                    $query->where("email", "LIKE", "%" . $filter["email"] . "%");
                }
            });
        }

        $result = $result->paginate($totalPerPage, ['*'], 'page', $page);
        return $result;
    }

    public function store(CreateUserDTO $dto): object|null
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->entity->forceCreate([
                "name" => $dto->name,
                "email" => $dto->email,
                "password" => Hash::make($dto->password),
            ]);

            return $user;
        });
    }

    public function update(UpdateUserDTO $dto): object|null
    {
        if (!$user = $this->entity->find($dto->id)) return null;

        return DB::transaction(function () use ($dto, $user) {
            if ($dto->password) $dto->password = Hash::make($dto->password);
            unset($dto->id);
            $dto = (array) $dto;
            $user->forceFill($dto)->save();
            return $user;
        });
    }

    public function delete($id): bool
    {
        $user = $this->findById($id);
        if (!$user) return false;

        $this->entity->where('id', $id)->delete();
        return true;
    }

    /**
     * Additional implementations
     */

    public function orderBy($column, $order = 'DESC')
    {
        $this->entity = $this->entity->orderBy($column, $order);
        return $this;
    }

    public function relationships(...$relationships)
    {
        $this->entity = $this->entity->with($relationships);
        return $this;
    }

    public function resolveEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new NotEntityDefined();
        }

        return app($this->entity());
    }
}
