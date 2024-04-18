<?php

namespace App\Repositories\Core\QueryBuilder;

use App\Repositories\Contracts\Product\ProductRepositoryInterface;
use App\Services\Product\ProductBaseQueryBuilderRepository;
use Illuminate\Http\Request;

class QueryBuilderProductRepository extends ProductBaseQueryBuilderRepository implements ProductRepositoryInterface
{
    protected $table = 'products';

    public function search(Request $request)
    {
        // return $this->db
        //     ->table($this->tb)
        //     ->where(function ($query) use ($data) {
        //         if (isset($data['title'])) {
        //             $query->where('title', $data['title']);
        //         }

        //         if (isset($data['url'])) {
        //             $query->orWhere('url', $data['url']);
        //         }

        //         if (isset($data['description'])) {
        //             $desc = $data['description'];
        //             $query->where('description', 'LIKE', "%{$desc}%");
        //         }
        //     })
        //     ->orderBy('id', 'desc')
        //     ->paginate();
    }
}
