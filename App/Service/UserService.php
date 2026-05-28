<?php

namespace App\Service;

use App\Contract\UserRepositoryInterface;
use App\Repository\UserRepository;

class UserService extends BaseService
{
    private UserRepositoryInterface $repo;

    public function __construct(?UserRepositoryInterface $repo = null)
    {
        // fallback for small projects
        $this->repo = $repo ?? new UserRepository($this->db());
    }

    // Business logic

    public function getUserById(int $id)
    {
        return $this->repo->findById($id);
    }

    public function getAllUsers(int $limit = 20, int $offset = 0)
    {
        return $this->repo->findAll($limit, $offset);
    }

    public function getUserByEmail(string $email)
    {
        return $this->repo->findByEmail($email);
    }

    public function register(array $data): void
    {
        if ($this->getUserByEmail($data['email'])) {
            throw new \InvalidArgumentException(json_encode([
                'email' => 'Email already exists'
            ]));
        }

        // Create User object
        $user = new \App\Model\User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']); // auto-hashed in model

        $this->repo->create($user->toArray());
    }

    public function login(array $data): void
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException(json_encode([
                'general' => 'Email and password are required'
            ]));
        }

        $user = $this->getUserByEmail($data['email']);

        if (!$user || !$user->verifyPassword($data['password'])) {
            throw new \RuntimeException(json_encode([
                'general' => 'Invalid email or password'
            ]));
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_email'] = $user->getEmail();
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
        session_destroy();
    }
}