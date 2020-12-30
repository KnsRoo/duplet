<?php
ini_set('memory_limit', '1024M');

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
ini_set('error_reporting', E_ALL);
session_start();

$loader = require_once __DIR__.'/../vendor/autoload.php';

$loader->addPsr4('Core\\', '../classes/Core/');
$loader->addPsr4('Back\\', '../classes/Back/');
$loader->addPsr4('Model\\', '../classes/Model/');

use Websm\Framework\Router\Router;
use Websm\Framework\Db\Config;
use Websm\Framework\Di;

Config::init(include __DIR__ . '/config.php');
include __DIR__ . '/services.php';

$di = Di\Container::instance();

Core\Users::init();
Websm\Framework\Notify::init();

$router = Router::init();

$auth = new Back\Auth\Auth;
$layout = new Back\Layout\Layout;

$router->mount('/admin', $auth->getRoutes());
$router->mount('/admin', $layout->getRoutes());
$router->start();
