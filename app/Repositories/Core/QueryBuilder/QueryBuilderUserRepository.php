<?php

namespace App\Repositories\Core\QueryBuilder;

use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Services\User\UserBaseQueryBuilderRepository;
use Illuminate\Http\Request;

class QueryBuilderUserRepository extends UserBaseQueryBuilderRepository implements UserRepositoryInterface
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
