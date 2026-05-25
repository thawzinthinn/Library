<?php

namespace App\Service;

use App\inc\Database;

/**
 * BaseService
 * Shared utilities for all services
 */
abstract class BaseService
{
    /**
     * Get database connection (shared)
     */
    protected function db()
    {
        return Database::getConnection();
    }
}