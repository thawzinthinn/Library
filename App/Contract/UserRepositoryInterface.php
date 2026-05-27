<?php

namespace App\Contract;

use App\Contract\BaseInterface;
use App\Model\User;

interface UserRepositoryInterface extends BaseInterface
{
    public function findByEmail(string $email);
    public function create(User $user): bool;

}