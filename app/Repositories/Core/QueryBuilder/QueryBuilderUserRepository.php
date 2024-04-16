<?php

namespace App\Repositories\Core\QueryBuilder;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Core\BaseQueryBuilderRepository;
use Illuminate\Http\Request;

class QueryBuilderUserRepository extends BaseQueryBuilderRepository implements UserRepositoryInterface
{
    protected $table = 'users';

    public function search(Request $request)
    {
        return $this->db
            ->table($this->tb)
            ->where(function ($query) use ($data) {
                if (isset($data['name'])) {
                    $query->where('name', $data['name']);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
