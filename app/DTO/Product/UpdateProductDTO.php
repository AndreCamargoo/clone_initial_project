<?php

namespace App\DTO\Product;

use App\Http\Requests\StoreUpdateProduct;

class UpdateProductDTO
{
    public function __construct(
        public string|int $id,
        public string $name,
        public string $url,
        public string $description,
        public float $price,
        public string|int $category_id,
        public object|null $image
    ) {
    }

    public static function makeFromRequest(StoreUpdateProduct $request): self
    {
        return new self(
            $request->id,
            $request->name,
            $request->url,
            $request->description,
            $request->price,
            $request->category_id,
            $request->image
        );
    }
}
