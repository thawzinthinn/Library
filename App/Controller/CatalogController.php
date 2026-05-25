<?php

namespace App\Controller;

use App\Service\CatalogService;

class CatalogController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        $data = $this->catalogService->getHomePageData();
        // var_dump($data);
        $this->view('home', $data);
    }

    public function index(): void
    {
        $data = $this->catalogService->getCatalogPage($_GET);
        // var_dump($data);
        $this->view('catalog', $data);
    }
}