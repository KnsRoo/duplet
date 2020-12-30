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
$loader->addPsr4('Exceptions\\', __DIR__.'/classes/Exceptions');

use Websm\Framework\Db\Config;
use Parsers\Product\ProductParser;
use Parsers\ProgressBar;

Config::init(include __ROOT.'/admin/config.php');

// $fileWithPriceTypes = glob(PATH_TO_FILES . '/import*.xml')[0];

// $directoryContent = glob(PATH_TO_FILES . '/*', GLOB_ONLYDIR);


// $progressBarTotal = count($directoryContent);
// $progressBar = new ProgressBar($progressBarTotal, 'Product parser');

// foreach ($directoryContent as $item) {
//     $pathToDir = $item;
//      $import = glob(PATH_TO_FILES . '/import*.xml');
//      $prices = glob(PATH_TO_FILES . '/offers*.xml');

// //     if (empty($import) || empty($prices))
// //         continue;
//     var_dump($import);
//     var_dump($prices);
    


    $productParser = new ProductParser(
        PATH_TO_FILES.'/Goods.xml',
        PATH_TO_FILES.'/PriceList.xml',
    );

    $productParser->parse();
    // $progressBar->makeStep();


// $progressBar->close();
