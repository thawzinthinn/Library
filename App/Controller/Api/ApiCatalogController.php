<?php

namespace App\Controller\Api;

use App\Service\CatalogService;
use App\Response\ApiResponse;

class ApiCatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(): void
    {
        try {

            $data = $this->catalogService->getCatalogPage($_GET);

            ApiResponse::success(
                $data,
                'Catalog fetched successfully'
            );

        } catch (\Throwable $e) {

            ApiResponse::error(
                $e->getMessage(),
                500
            );
        }
    }
}