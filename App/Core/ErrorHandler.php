<?php

namespace App\Core;

class ErrorHandler
{
    public static function handle(\Throwable $e): void
    {
        error_log($e->getMessage());

        // Detect API request
        $isApi = str_contains($_SERVER['REQUEST_URI'], '/api');

        if ($isApi) {
            self::handleApi($e);
            return;
        }

        self::handleWeb($e);
    }

    /**
     * Handle normal MVC web requests
     */
    private static function handleWeb(\Throwable $e): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['error'] = self::format($e);
        $_SESSION['old'] = $_POST;

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    /**
     * Handle API requests
     */
    private static function handleApi(\Throwable $e): void
    {
        http_response_code(500);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);

        exit;
    }

    private static function format(\Throwable $e): array
    {
        $decoded = json_decode($e->getMessage(), true);

        if (is_array($decoded)) {
            return $decoded;
        }

        return [
            'general' => $e->getMessage()
        ];
    }
}