<?php

namespace App\Controller;

use App\Http\Requests\BaseRequest;

/**
 * BaseController
 * Shared controller utilities only
 */
abstract class BaseController
{
    protected function validateRequest(string $requestClass): array
    {
        if (!class_exists($requestClass)) {
            throw new \Exception("Your Request not found: $requestClass");
        }

        /** @var BaseRequest $request */
        $request = new $requestClass();

        if (!$request->validate()) {

            // return errors in Laravel style
            throw new \Exception(json_encode($request->errors()));
        }

        return $request->data();
    }
    /**
     * Render view with data
     */
    protected function view(string $path, array $data = []): void
    {
        extract($data);
        require BASE_PATH . '/view/' . $path . '.php';
    }

    /**
     * Redirect helper
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Safe GET input helper
     */
    protected function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Safe POST input helper
     */
    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /*
     * Check if user logged in
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /*
     * Protect pages
     */
    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {

            $_SESSION['error'] =
                'Please login first!';

            $this->redirect(
                'index.php?page=login'
            );
        }
    }
}