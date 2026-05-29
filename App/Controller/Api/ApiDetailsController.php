<?php

namespace App\Controller\Api;

use App\Service\CatalogService;
use App\Response\ApiResponse;

class ApiDetailsController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        try {

            $id = filter_input(
                INPUT_GET,
                'id',
                FILTER_VALIDATE_INT
            );

            if (!$id) {

                ApiResponse::error(
                    'Invalid ID',
                    400
                );

                return;
            }

            $item = $this->catalogService->getSingleItem($id);

            if (empty($item)) {

                ApiResponse::error(
                    'Item not found',
                    404
                );

                return;
            }

            ApiResponse::success(
                $item,
                'Item fetched successfully'
            );

        } catch (\Throwable $e) {

            ApiResponse::error(
                $e->getMessage(),
                500
            );
        }
    }
}