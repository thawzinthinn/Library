<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        $path = rtrim($path, '/');

        if (!isset($this->routes[$method][$path])) {

            echo "ROUTE NOT FOUND: " . $path;
            exit;
        }

        [$controller, $action] = $this->routes[$method][$path];

        $instance = new $controller();
        $instance->$action();
    }
}