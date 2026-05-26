<?php

namespace App\Core;

class ErrorHandler
{
    public static function handle(\Throwable $e): void
    {
        // You can log later if needed
        error_log($e->getMessage());

        // Store error in session (like Laravel flash)
        $_SESSION['error'] = self::format($e);

        $_SESSION['old'] = $_POST;

        // Redirect back automatically
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    private static function format(\Throwable $e): array
    {
        $decoded = json_decode($e->getMessage(), true);

        // validation error (array)
        if (is_array($decoded)) {
            return $decoded;
        }

        // normal error
        return [
            'general' => $e->getMessage()
        ];
    }
}