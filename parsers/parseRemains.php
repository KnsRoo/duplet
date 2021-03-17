<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('memory_limit','512M');

const __ROOT = __DIR__.'/../public';
//const PATH_TO_FILES = __DIR__.'/../Sync/1cbitrix';
const PATH_TO_FILES = __DIR__.'/../Sync/webdata';

$loader = require_once __ROOT.'/vendor/autoload.php';

$loader->addPsr4('Model\\', __ROOT.'/classes/Model');
$loader->addPsr4('Core\\', __ROOT.'/classes/Core');
$loader->addPsr4('Parsers\\', __DIR__.'/classes/Parsers');
$loader->addPsr4('ProgressBar\\', __DIR__.'/classes/ProgressBar');
$loader->addPsr4('Exceptions\\', __DIR__.'/classes/Exceptions');

use Websm\Framework\Db\Config;
use Parsers\Product\RemainsParser;
use Parsers\ProgressBar;

Config::init(include __ROOT.'/admin/config.php');

$productParser = new RemainsParser(
    PATH_TO_FILES.'/Goods.xml',
);

$productParser->parse();
