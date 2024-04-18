<?php

namespace App\DTO\Category;

use App\Http\Requests\StoreUpdateCategory;

class CreateCategoryDTO
{
    public function __construct(
        public string $title,
        public string|null $url,
        public string $description,
    ) {
    }

    public static function makeFromRequest(StoreUpdateCategory $request): self
    {
        return new self(
            $request->title,
            $request->url,
            $request->description,
        );
    }
}
