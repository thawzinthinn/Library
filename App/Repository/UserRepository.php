<?php

namespace App\Repository;

use App\Contract\UserRepositoryInterface;
use PDO;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected string $table = 'users'; // table name
    protected string $primaryKey = 'id';

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
        INSERT INTO users (name, email, password)
        VALUES (:name, :email, :password)
    ");

        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password']
        ]);
    }

}