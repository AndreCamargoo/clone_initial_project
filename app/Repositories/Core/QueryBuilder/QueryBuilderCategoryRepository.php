<?php

namespace App\Repositories\Core\QueryBuilder;

use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Services\Category\CategoryBaseQueryBuilderRepository;

class QueryBuilderCategoryRepository extends CategoryBaseQueryBuilderRepository implements CategoryRepositoryInterface
{
    protected $table = 'categories';

    public function search(array $data)
    {
        return $this->db
            ->table($this->tb)
            ->where(function ($query) use ($data) {
                if (isset($data['title'])) {
                    $query->where('title', $data['title']);
                }

                if (isset($data['url'])) {
                    $query->orWhere('url', $data['url']);
                }

                if (isset($data['description'])) {
                    $desc = $data['description'];
                    $query->where('description', 'LIKE', "%{$desc}%");
                }
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function productsByCategoryId($id)
    {
        return $this->db
            ->table('products')
            ->where('category_id', $id)
            ->get();
    }
}
