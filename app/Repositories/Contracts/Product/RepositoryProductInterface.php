<?php

namespace App\Repositories\Contracts\Product;

use App\DTO\Product\CreateProductDTO;
use App\DTO\Product\UpdateProductDTO;

interface RepositoryProductInterface
{
    public function getAll();
    public function findById(string|int $id);
    public function findWhere(string $column, string $value);
    public function findWhereFirst(string $column, string $value);
    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null);
    public function store(CreateProductDTO $dto);
    public function update(UpdateProductDTO $dto);
    public function delete(string|int $id);
    public function orderBy($column, $order = 'DESC');
}
