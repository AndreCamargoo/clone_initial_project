<?php

namespace App\DTO\Category;

use App\Http\Requests\StoreUpdateCategory;

class UpdateCategoryDTO
{
    public function __construct(
        public string|int $id,
        public string $title,
        public string|null $url,
        public string $description,
    ) {
    }

    public static function makeFromRequest(StoreUpdateCategory $request): self
    {
        return new self(
            $request->id,
            $request->title,
            $request->url,
            $request->description
        );
    }
}
