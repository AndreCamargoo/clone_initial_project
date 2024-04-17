<?php

namespace App\Repositories\Core;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use Illuminate\Support\Facades\DB;
use App\Repositories\Exceptions\NotEntityDefined;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Hash;
use stdClass;

class BaseEloquentRespository implements RepositoryInterface
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

    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null): object|null
    {
        return $this->entity->paginate($totalPerPage, ['*'], 'page', $page);
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
