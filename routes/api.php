<?php

use App\Core\Router;

use App\Controller\Api\ApiCatalogController;
use App\Controller\Api\ApiDetailsController;
use App\Controller\Api\ApiSuggestController;
use App\Controller\Api\ApiUserController;

$router = new Router();

/*
|--------------------------------------------------------------------------
| Catalog APIs
|--------------------------------------------------------------------------
*/

$router->get(
    '/api/catalog',
    [ApiCatalogController::class, 'index']
);

$router->get(
    '/api/details',
    [ApiDetailsController::class, 'show']
);

/*
|--------------------------------------------------------------------------
| Suggest API
|--------------------------------------------------------------------------
*/

$router->post(
    '/api/suggest',
    [ApiSuggestController::class, 'store']
);

/*
|--------------------------------------------------------------------------
| User APIs
|--------------------------------------------------------------------------
*/
$router->get('/users', [ApiUserController::class, 'index']);
$router->get('/user', [ApiUserController::class, 'show']);
$router->post('/users', [ApiUserController::class, 'create']);
$router->post('/user-update', [ApiUserController::class, 'update']);
$router->post('/user-delete', [ApiUserController::class, 'delete']);

return $router;