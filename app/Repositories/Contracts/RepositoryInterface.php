<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function findWhere($column, $value);
    public function findWhereFirst($column, $value);
    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function orderBy($column, $order = 'DESC');
}
