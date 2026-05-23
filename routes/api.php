<?php
use App\Controller\Api\ApiCatalogController;
use App\Controller\Api\ApiDetailsController;
use App\Controller\Api\ApiSuggestController;

header('Content-Type: application/json');

$api = $_GET['api'] ?? '';

switch ($api) {

    case 'catalog':

        $controller = new ApiCatalogController($catalogService);
        $controller->index();
        break;

    case 'details':

        $controller = new ApiDetailsController($catalogService);
        $controller->show();
        break;
        
    case 'suggest':

        $controller = new ApiSuggestController($formatService);
        $controller->store();
        break;

    default:

        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'API route not found'
        ]);
}