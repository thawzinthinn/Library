<?php

namespace App\Mapper;

use App\Model\User;
use App\DTO\UserDTO;

class UserMapper
{
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            id: $user->getId(),
            name: $user->getName(),
            email: $user->getEmail()
        );
    }

    public static function toArray(UserDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'email' => $dto->email
        ];
    }
}