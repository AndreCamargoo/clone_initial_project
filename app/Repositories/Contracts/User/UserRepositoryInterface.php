<?php

namespace App\Repositories\Contracts\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface extends RepositoryUserInterface
{
    public function search(Request $request);
}
