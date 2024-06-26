<?php

namespace App\Repositories\Core\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\Category\CategoryRepositoryInterface;
use App\Services\Category\CategoryBaseEloquentRespository;

class EloquentCategoryRepository extends CategoryBaseEloquentRespository implements CategoryRepositoryInterface
{
    public function entity()
    {
        return Category::class;
    }

    public function search(array $data)
    {
        return $this->entity()->where(function ($query) use ($data) {
            if (isset($data["title"])) {
                $query->where('title', $data["title"]);
            }

            if (isset($data["url"])) {
                $query->where("url", $data["url"]);
            }

            if (isset($data["description"])) {
                $query->where("description", "LIKE", "%{$data["url"]}%");
            }
        })->orderBy("id", "DESC")->paginate(10);
    }

    public function productsByCategoryId($id)
    {
    }
}
