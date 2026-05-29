<?php

namespace App\Controller\Api;

use App\Service\UserService;

class ApiUserController
{
    private UserService $service;

    public function __construct(?UserService $service = null)
    {
        $this->service = $service ?? new UserService();
    }

    /**
     * GET /api/users
     */
    public function index(): void
    {
        header('Content-Type: application/json');

        $users = $this->service->getAllUsers();
        echo $users;
        exit;

        echo json_encode([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * GET /api/users/show?id=1
     */
    public function show(): void
    {
        header('Content-Type: application/json');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new \Exception("Invalid user id");
        }

        $user = $this->service->findById($id);

        if (!$user) {
            throw new \Exception("User not found");
        }

        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * POST /api/users/create
     */
    public function create(): void
    {
        header('Content-Type: application/json');

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
        ];

        $result = $this->service->create($data);

        echo json_encode([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $result
        ]);
    }

    /**
     * POST /api/users/update?id=1
     */
    public function update(): void
    {
        header('Content-Type: application/json');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new \Exception("Invalid user id");
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];

        $updated = $this->service->update($id, $data);

        if (!$updated) {
            throw new \Exception("Failed to update user");
        }

        echo json_encode([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * POST /api/users/delete?id=1
     */
    public function delete(): void
    {
        header('Content-Type: application/json');

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new \Exception("Invalid user id");
        }

        $deleted = $this->service->delete($id);

        if (!$deleted) {
            throw new \Exception("Failed to delete user");
        }

        echo json_encode([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}