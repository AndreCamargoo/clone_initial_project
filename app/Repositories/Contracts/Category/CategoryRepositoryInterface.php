<?php

namespace App\Repositories\Contracts\Category;

interface CategoryRepositoryInterface extends RepositoryCategoryInterface
{
    public function search(array $data);
    public function productsByCategoryId($id);
}
