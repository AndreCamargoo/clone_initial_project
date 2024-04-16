<?php

namespace App\Repositories\Core\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Core\BaseEloquentRespository;
use Illuminate\Http\Request;

class EloquentUserRepository extends BaseEloquentRespository implements UserRepositoryInterface
{
    public function entity()
    {
        return User::class;
    }

    public function search(Request $request)
    {
        return $this->entity->where(function ($query) use ($request) {
                if ($request->name) {
                    $filter = $request->name;

                    $query->where(function ($querySub) use ($filter) {
                        $querySub->where("name", $filter)
                            ->orWhere("email", "LIKE", "%{$filter}%");
                    });
                }
            })->paginate(10);
        // ->toSql();
    }
}
