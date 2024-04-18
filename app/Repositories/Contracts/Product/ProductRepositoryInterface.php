<?php

namespace App\Repositories\Contracts\Product;

use Illuminate\Http\Request;

interface ProductRepositoryInterface extends RepositoryProductInterface
{
    public function search(Request $request);
}
