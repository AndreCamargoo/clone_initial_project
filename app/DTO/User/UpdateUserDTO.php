<?php

namespace App\DTO\User;

use App\Http\Requests\StoreUpdateUser;

class UpdateUserDTO
{
    public function __construct(
        public string|int $id,
        public string $name,
        public string $email,
        public string $password
    ) {
    }

    public static function makeFromRequest(StoreUpdateUser $request): self
    {
        return new self(
            $request->id,
            $request->name,
            $request->email,
            $request->password
        );
    }
}
