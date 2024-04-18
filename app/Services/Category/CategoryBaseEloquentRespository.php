<?php

namespace App\Services\Category;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\UpdateCategoryDTO;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\Category\RepositoryCategoryInterface;
use App\Repositories\Exceptions\NotEntityDefined;

class CategoryBaseEloquentRespository implements RepositoryCategoryInterface
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

    public function store(CreateCategoryDTO $dto): object|null
    {
        return DB::transaction(function () use ($dto) {
            $category = $this->entity->forceCreate([
                "title" => $dto->title,
                "url" => Str::slug($dto->title),
                "description" => $dto->description
            ]);

            return $category;
        });
    }

    public function update(UpdateCategoryDTO $dto): object|null
    {
        if (!$category = $this->entity->find($dto->id)) return null;

        return DB::transaction(function () use ($dto, $category) {
            unset($dto->id);
            $dto = (array) $dto;
            $category->forceFill($dto)->save();
            return $category;
        });
    }

    public function delete($id): bool
    {
        $product = $this->findById($id);
        if (!$product) return false;

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
