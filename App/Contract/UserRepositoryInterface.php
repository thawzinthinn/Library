<?php

namespace App\Contract;

use App\Contract\BaseInterface;

interface UserRepositoryInterface extends BaseInterface
{
    public function findByEmail(string $email);
}