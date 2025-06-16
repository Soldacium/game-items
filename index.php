<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Expires: Sun, 02 Jan 1990 00:00:00 GMT');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'src/Config/Database.php';
require_once 'src/Router.php';
require_once 'src/Controller/BaseController.php';
require_once 'src/Controller/AuthController.php';
require_once 'src/Controller/ItemController.php';
require_once 'src/Controller/AccountController.php';
require_once 'src/Model/BaseModel.php';
require_once 'src/Model/User.php';
require_once 'src/Model/Item.php';
require_once 'src/Model/Profile.php';
require_once 'src/Utils/AssetHelper.php';
require_once 'src/Controller/ManagementController.php';
require_once 'src/Repository/BaseRepository.php';
require_once 'src/Repository/ItemRepository.php';

use App\Router;
use App\Controller\AuthController;
use App\Controller\ItemController;
use App\Controller\AccountController;
use App\Controller\ManagementController;

$router = Router::getInstance();

// Auth routes
$router->addRoute('GET', '/login', AuthController::class, 'login');
$router->addRoute('POST', '/login', AuthController::class, 'login');
$router->addRoute('GET', '/register', AuthController::class, 'register');
$router->addRoute('POST', '/register', AuthController::class, 'register');
$router->addRoute('GET', '/logout', AuthController::class, 'logout');

// Management routes
$router->addRoute('GET', '/management', ManagementController::class, 'index', true);
$router->addRoute('GET', '/management/profile', ManagementController::class, 'profile', true);
$router->addRoute('GET', '/management/account', ManagementController::class, 'account', true);
$router->addRoute('GET', '/management/activity', ManagementController::class, 'activity', true);
$router->addRoute('POST', '/management/profile/update', ManagementController::class, 'updateProfile', true);
$router->addRoute('POST', '/management/profile/avatar', ManagementController::class, 'updateAvatar', true);
$router->addRoute('POST', '/management/account/update', ManagementController::class, 'updateAccount', true);
$router->addRoute('POST', '/management/account/password', ManagementController::class, 'updatePassword', true);

// Item management routes
$router->addRoute('GET', '/management/items', ManagementController::class, 'items', true);
$router->addRoute('GET', '/management/items/new', ManagementController::class, 'newItem', true);
$router->addRoute('POST', '/management/items/new', ManagementController::class, 'newItem', true);
$router->addRoute('GET', '/management/items/edit/([0-9]+)', ManagementController::class, 'editItem', true);
$router->addRoute('POST', '/management/items/edit/([0-9]+)', ManagementController::class, 'editItem', true);
$router->addRoute('POST', '/management/items/delete/([0-9]+)', ManagementController::class, 'deleteItem', true);

// Account routes (legacy - to be migrated)
$router->addRoute('GET', '/account', AccountController::class, 'index');
$router->addRoute('POST', '/account/update-profile', AccountController::class, 'updateProfile');
$router->addRoute('POST', '/account/update-password', AccountController::class, 'updatePassword');

// Item routes
$router->addRoute('GET', '/', ItemController::class, 'landing');
$router->addRoute('GET', '/items', ItemController::class, 'index');
$router->addRoute('GET', '/api/items', ItemController::class, 'index');
$router->addRoute('GET', '/items/:id', ItemController::class, 'show');
$router->addRoute('GET', '/items/create', ItemController::class, 'create', true);
$router->addRoute('POST', '/items/create', ItemController::class, 'create', true);
$router->addRoute('GET', '/items/:id/edit', ItemController::class, 'edit', true);
$router->addRoute('POST', '/items/:id/edit', ItemController::class, 'edit', true);
$router->addRoute('POST', '/items/:id/delete', ItemController::class, 'delete', true);

// Blob routes
$router->addRoute('GET', '/blob/([0-9]+)', ItemController::class, 'serveBlob');

// Handle the request and get the content
$content = $router->route();

// Output the content
if ($content !== false && $content !== null) {
    echo $content;
}
