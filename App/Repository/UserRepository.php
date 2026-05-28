<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;
use App\Model\User;
use PDO;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $table = 'users';
    protected string $model = User::class;

    /*
    |--------------------------------------------------------------------------
    | FIND BY EMAIL (RETURN MODEL)
    |--------------------------------------------------------------------------
    */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare(
            "CALL sp_find_user_by_email(:email)"
        );

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (!$data) {
            return null;
        }

        return User::fromArray($data);
    }
}