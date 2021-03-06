<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', E_ALL);

$loader = require_once __DIR__ . '/vendor/autoload.php';

$loader->addPsr4('API\\', 'classes/API/');
$loader->addPsr4('Back\\', 'classes/Back/');
$loader->addPsr4('Model\\', 'classes/Model/');
$loader->addPsr4('Core\\', 'classes/Core/');
$loader->addPsr4('Front\\', 'classes/Front/');
$loader->addPsr4('Components\\', 'classes/Components/');

use Websm\Framework\Db\Config;
use Websm\Framework\Di\Container as Di;
use Websm\Framework\Router\Router;

Config::init(include __DIR__ . '/admin/config.php');

include 'services.php';

$di = Di::instance();

$router = Router::init();

$catalogAPI = new API\Catalog\V3\Controller;
$newsAPI = new API\News\V4\Controller;
$pagesAPI = new API\Pages\V1\Controller;
$cartAPI = new API\Cart\V1\Controller;
$authAPI = new API\Auth\V1\Basic\Controller;
$userAPI = $di->get('user-api');
$jwtAPI = new API\Auth\V1\JWT\Controller;
$ordersAPI = $di->get('orders-api');
$favoritesAPI = new API\Favorites\Controller;

$router->mount('/api/catalog', $catalogAPI->getRoutes());
$router->mount('/api/news', $newsAPI->getRoutes());
$router->mount('/api/pages', $pagesAPI->getRoutes());
$router->mount('/api/cart', $cartAPI->getRoutes());
$router->mount('/api/auth/JWT', $jwtAPI->getRoutes());
$router->mount('/api/auth/basic', $authAPI->getRoutes());
$router->mount('/api/user', $userAPI->getRoutes());
$router->mount('/api/orders', $ordersAPI->getRoutes());
$router->mount('/api/favorites', $favoritesAPI->getRoutes());


$router->mount('/news', (new Front\News\Controller)->getRoutes());
$router->mount('/catalog', (new Front\Catalog\Controller)->getRoutes());
$router->mount('/sitemap.xml', (new Front\Sitemap\Sitemap)->getRoutes());
$router->mount('/cart', (new Front\Cart\Controller)->getRoutes());
$router->mount('/favorite', (new Front\Favorite\Controller)->getRoutes());
$router->mount('/lk', (new Front\lk\Controller)->getRoutes());
$router->mount('/Contacts', (new Front\Contacts\Controller)->getRoutes());
$router->mount('/Stocks', (new Front\Stocks\Controller)->getRoutes());

$router->mount('/', (new Front\Main\Controller)->getRoutes());
$router->mount('/', (new Front\Pages\Controller)->getRoutes());

$router->start();

$di->get('layout')->renderPage();
