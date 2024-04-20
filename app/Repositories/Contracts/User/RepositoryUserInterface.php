<?php

namespace App\Repositories\Contracts\User;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use Illuminate\Http\Request;

interface RepositoryUserInterface
{
    public function getAll();
    public function findById(string|int $id);
    public function findWhere(string $column, string $value);
    public function findWhereFirst(string $column, string $value);
    public function paginate(int $page = 1, int $totalPerPage = 15, Request $filter = null);
    public function store(CreateUserDTO $dto);
    public function update(UpdateUserDTO $dto);
    public function delete(string|int $id);
    public function orderBy($column, $order = 'DESC');
}
