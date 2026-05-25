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
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('Name, email and password are required');
        }

        if ($this->getUserByEmail($data['email'])) {
            throw new \InvalidArgumentException('Email is already registered');
        }

        $this->createUser($data);
    }

    public function login(string $email, string $password): void
    {
        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Email and password are required');
        }

        $user = $this->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new \RuntimeException('Invalid email or password');
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
        session_destroy();
    }

    public function createUser(array $data)
    {
        // Example business logic: password hashing
        if (!isset($data['password'])) {
            throw new \InvalidArgumentException("Password is required");
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt = $this->db()->prepare("
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ");

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->execute();
    }
}