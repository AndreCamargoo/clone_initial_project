<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function search(Request $request);
}
