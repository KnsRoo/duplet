<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('error_reporting', E_ALL);

const __ROOT = __DIR__.'/../public';
const PATH_TO_FILES = __DIR__.'/../Sync/webdata';

$loader = require_once __ROOT.'/vendor/autoload.php';

$loader->addPsr4('Model\\', __ROOT.'/classes/Model');
$loader->addPsr4('Core\\', __ROOT.'/classes/Core');
$loader->addPsr4('Parsers\\', __DIR__.'/classes/Parsers');
$loader->addPsr4('Exceptions\\', __DIR__.'/classes/Exceptions');

use Websm\Framework\Db\Config;
use Parsers\Group\GroupParser;

Config::init(include __ROOT.'/admin/config.php');

$file = PATH_TO_FILES.'/GoodsHierarchy.xml';
$groupParser = new GroupParser($file);
$groupParser->parse();
