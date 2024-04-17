<?php

namespace App\DTO\User;

use App\Http\Requests\StoreUpdateUser;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }

    public static function makeFromRequest(StoreUpdateUser $request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->password
        );
    }
}
