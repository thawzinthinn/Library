<?php

namespace App\Controller;

/**
 * BaseController
 * Shared controller utilities only
 */
abstract class BaseController
{
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
}