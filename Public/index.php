<?php

/**
 * Application Entry Point
 */

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;

use App\inc\Database;

use App\Repository\CatalogRepository;
use App\Repository\FormatRepository;

use App\Service\CatalogService;
use App\Service\FormatService;

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

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

$catalogService = new CatalogService($catalogRepo);
$formatService = new FormatService($formatRepo);

/*
|--------------------------------------------------------------------------
| ROUTING
|--------------------------------------------------------------------------
*/

$isApi = isset($_GET['api']);

if ($isApi) {

    require BASE_PATH . '/routes/api.php';

} else {

    require BASE_PATH . '/routes/web.php';
}