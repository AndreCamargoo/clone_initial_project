<?php

namespace App\DTO\Product;

use App\Http\Requests\StoreUpdateProduct;

class CreateProductDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public string|int $category_id
    ) {
    }

    public static function makeFromRequest(StoreUpdateProduct $request): self
    {
        return new self(
            $request->name,
            $request->description,
            $request->price,
            $request->category_id
        );
    }
}
