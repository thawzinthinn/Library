
<?php
/**
 * Main application entry point.
 * Initializes dependencies, services, and application routing.
 */
/*
//Report simple running errors
error_reporting(E_ALL);
//Make sure they are on screen
ini_set('display_errors',1);
//HTML formatted errors
ini_set('html_errors',1);
        OR
use @ in front of error
*/
define('BASE_PATH', dirname(__DIR__));// parent directory path.

require_once BASE_PATH . '/vendor/autoload.php'; // IS A folder created by composer
require_once BASE_PATH . '/inc/Database.php';
require_once BASE_PATH . '/inc/CustomPath.php';
require_once BASE_PATH . '/Controller/Api/ApiCatalogController.php';
require_once BASE_PATH . '/Controller/Api/ApiDetailsController.php';
require_once BASE_PATH . '/Controller/Api/ApiSuggestController.php';

use Dotenv\Dotenv; //Import Dotenv class
$dotenv = Dotenv::createImmutable(dirname(__DIR__)); // create read only// encapsulation
$dotenv->load(); //  load .env variable in php

/*BUILD SHARED OBJECTS*/

$db = Database::getConnection();// encapsulation

/* Repositories */
$catalogRepo = new CatalogRepository($db); // dependancy invertion
$formatRepo  = new FormatRepository($db);

/* Services */
$catalogService = new CatalogService($catalogRepo); 
// CatalogService
    // ↓
//CatalogRepository
   // ↓
// Database
//service depends on the repository. “Give CatalogRepository to CatalogService.”
$formatService  = new FormatService($formatRepo);

/* ROUTING */

$isApi = isset($_GET['api']);

if ($isApi) {

    require BASE_PATH . '/routes/api.php';

} else {

    require BASE_PATH . '/routes/web.php';
}

