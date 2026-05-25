<?php

namespace App\Controller\Api;
use App\Service\CatalogService;

class ApiDetailsController
{
    private CatalogService $catalogService;
    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        header('Content-Type: application/json');

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Invalid ID'
            ]);

            return;
        }

        $item = $this->catalogService->getSingleItem($id);

        if (empty($item)) {

            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Item not found'
            ]);

            return;
        }

        echo json_encode([
            'success' => true,
            'data' => $item
        ]);
    }
}