<?php

/**
 * Application Entry Point
 */

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\ErrorHandler;

use App\inc\Database;

use App\Repository\CatalogRepository;
use App\Repository\FormatRepository;
use App\Repository\UserRepository;

use App\Service\CatalogService;
use App\Service\FormatService;
use App\Service\UserService;

/*
|--------------------------------------------------------------------------
| GLOBAL ERROR HANDLER
|--------------------------------------------------------------------------
*/

set_exception_handler([ErrorHandler::class, 'handle']);

/*
|--------------------------------------------------------------------------
| LOAD ENV
|--------------------------------------------------------------------------
*/

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$db = Database::getConnection();

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

$catalogRepo = new CatalogRepository($db);
$formatRepo = new FormatRepository($db);
$userRepo = new UserRepository($db);

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

$catalogService = new CatalogService($catalogRepo);
$formatService = new FormatService($formatRepo);
$userService = new UserService($userRepo);

/*
|--------------------------------------------------------------------------
| ROUTING
|--------------------------------------------------------------------------
*/
// $requestUri = $_SERVER['REQUEST_URI'];

/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/
$api = $_GET['api'] ?? null;

if ($api !== null) {

    require BASE_PATH . '/routes/api.php';

    $router->dispatch(
        '/' . $api,
        $_SERVER['REQUEST_METHOD']
    );

    exit;
}
/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

require BASE_PATH . '/routes/web.php';