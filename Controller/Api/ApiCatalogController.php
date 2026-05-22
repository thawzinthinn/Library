<?php

class ApiCatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        $data = $this->catalogService->getCatalogPage($_GET);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}