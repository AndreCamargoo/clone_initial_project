<?php

namespace App\Repositories\Contracts\Category;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\UpdateCategoryDTO;
use Illuminate\Http\Request;

interface RepositoryCategoryInterface
{
    public function getAll();
    public function findById(string|int $id);
    public function findWhere(string $column, string $value);
    public function findWhereFirst(string $column, string $value);
    public function paginate(int $page = 1, int $totalPerPage = 15, Request $filter = null);
    public function store(CreateCategoryDTO $dto);
    public function update(UpdateCategoryDTO $dto);
    public function delete(string|int $id);
    public function orderBy($column, $order = 'DESC');
}
