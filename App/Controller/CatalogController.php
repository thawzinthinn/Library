<?php

namespace App\Controller;

use App\Service\CatalogService;

class CatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        $data = $this->catalogService->getHomePageData();

        extract($data);

        require BASE_PATH . '/view/home.php';
    }

    public function index(): void
    {
        $data = $this->catalogService->getCatalogPage($_GET);

        extract($data);

        require BASE_PATH . '/view/catalog.php';
    }
}