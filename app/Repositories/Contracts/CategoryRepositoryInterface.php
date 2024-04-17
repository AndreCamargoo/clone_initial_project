<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function search(array $data);
    public function productsByCategoryId($id);
}
