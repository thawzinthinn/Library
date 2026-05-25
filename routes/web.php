<?php

use App\Controller\CatalogController;
use App\Controller\DetailsController;
use App\Controller\SuggestController;
use App\Controller\UserController;

use App\Repository\UserRepository;
use App\Service\UserService;

$page = $_GET['page'] ?? 'home';

/*
|--------------------------------------------------------------------------
| USER DEPENDENCIES
|--------------------------------------------------------------------------
*/

$userRepo = new UserRepository($db);
$userService = new UserService($userRepo);

/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

switch ($page) {

    /*
    |--------------------------------------------------------------------------
    | HOME PAGE
    |--------------------------------------------------------------------------
    */
    case 'home':

        $controller = new CatalogController($catalogService);
        $controller->home();

        break;

    /*
    |--------------------------------------------------------------------------
    | CATALOG
    |--------------------------------------------------------------------------
    */
    case 'catalog':

        $controller = new CatalogController($catalogService);
        $controller->index();

        break;

    /*
    |--------------------------------------------------------------------------
    | DETAILS
    |--------------------------------------------------------------------------
    */
    case 'details':

        $controller = new DetailsController($catalogService);
        $controller->show();

        break;

    /*
    |--------------------------------------------------------------------------
    | SUGGEST
    |--------------------------------------------------------------------------
    */
    case 'suggest':

        $controller = new SuggestController($formatService);
        $controller->index();

        break;

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    case 'login':

        $controller = new UserController($userService);
        $controller->login();

        break;

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    case 'register':

        $controller = new UserController($userService);
        $controller->register();

        break;

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    case 'logout':

        $controller = new UserController($userService);
        $controller->logout();

        break;

    /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    */
    default:

        $controller = new CatalogController($catalogService);
        $controller->home();

        break;
}