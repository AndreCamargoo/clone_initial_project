<?php

namespace App\Services\Product;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;
use App\Repositories\Exceptions\NotEntityDefined;
use App\Repositories\Contracts\Product\RepositoryProductInterface;
use Illuminate\Http\Request;

class ProductBaseEloquentRespository implements RepositoryProductInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->resolveEntity();
    }

    public function getAll()
    {
        return $this->entity->all();
    }

    public function findById(string|int $id)
    {
        return $this->entity->find($id);
    }

    public function findWhere(string $column, string $value)
    {
        return $this->entity->where($column, $value)->get();
    }

    public function findWhereFirst(string $column, string $value)
    {
        return $this->entity->where($column, $value)->first();
    }

    public function paginate(int $page = 1, int $totalPerPage = 15, Request $filter = null)
    {
        $result = $this->entity;

        if ($filter["name"] || $filter["price"]) {
            $result = $result->where(function ($query) use ($filter) {
                if ($filter["name"]) {
                    $query->where("name", "LIKE", "%" . $filter["name"] . "%");
                    // $query->orWhere('last_name', $filter["name"]);
                }
            });
        }

        $result = $result->paginate($totalPerPage, ['*'], 'page', $page);
        return $result;
    }

    public function store(CreateProductDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            $product = $this->entity->forceCreate([
                "category_id" => $dto->category_id,
                "name" => $dto->name,
                "url" => Str::slug($dto->name),
                "description" => $dto->description,
                "price" => $dto->price
            ]);

            return $product;
        });
    }

    public function update(UpdateProductDTO $dto)
    {
        if (!$product = $this->entity->find($dto->id)) return null;

        return DB::transaction(function () use ($dto, $product) {
            unset($dto->id);
            $dto = (array) $dto;
            $product->forceFill($dto)->save();
            return $product;
        });
    }

    public function delete($id)
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
