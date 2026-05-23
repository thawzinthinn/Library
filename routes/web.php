<?php
use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;

$page = $_GET['page'] ?? 'home';

switch ($page) {

    case 'details':

        $controller = new DetailsController($catalogService);
        $controller->show();
        break;

    case 'suggest':

        $controller = new SuggestController($formatService);
        $controller->index();
        break;

    case 'catalog':

        $controller = new CatalogController($catalogService);
        $controller->index();
        break;

    default:

        $controller = new CatalogController($catalogService);
        $controller->home();
}