<?php

namespace App\Repositories\Contracts\Category;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\UpdateCategoryDTO;

interface RepositoryCategoryInterface
{
    public function getAll(): object|null;
    public function findById(string|int $id): object|null;
    public function findWhere(string $column, string $value): object|null;
    public function findWhereFirst(string $column, string $value): object|null;
    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null): object|null;
    public function store(CreateCategoryDTO $dto): object|null;
    public function update(UpdateCategoryDTO $dto): object|null;
    public function delete(string|int $id): bool;
    public function orderBy($column, $order = 'DESC');
}
