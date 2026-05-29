<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email
    ) {}
}