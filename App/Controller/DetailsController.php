<?php

namespace App\Controller;

use App\Service\CatalogService;

class DetailsController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        $this->requireLogin();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->redirect(BASE_URL . "/Public/index.php?page=catalog");
        }

        $item = $this->catalogService->getSingleItem($id);

        if (empty($item)) {
            $this->redirect(BASE_URL . "/Public/index.php?page=catalog");
        }

        $this->view('details', [
            'item' => $item,
            'pageTitle' => $item['title'],
            'section' => $item['category']
        ]);
    }
}