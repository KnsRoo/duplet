<?php

namespace websm;

include __DIR__.'/Psr4AutoloaderClass.php';

use Psr4AutoloaderClass;

defined('SM_ENV') or define('SM_ENV', 'production');

defined('SM_PATH') or define('SM_PATH', __DIR__);

defined('SM_CACHE_DIR') or define('SM_CACHE_DIR', __DIR__.'/../cache');

defined('SM_PUBLIC_DIR') or define('SM_PUBLIC_DIR', __DIR__.'/../public');

defined('SM_MODULES_DIR') or define('SM_MODULES_DIR', __DIR__.'/../modules');

defined('SM_VIEWS_DIR') or define('SM_VIEWS_DIR', __DIR__.'/views');

defined('SM_ADVANCED_VIEWS_DIR') or define('SM_ADVANCED_VIEWS_DIR', __DIR__.'/../views');

defined('SM_VENDOR_DIR') or define('SM_VENDOR_DIR', __DIR__.'/../vendor');

if (file_exists(SM_VENDOR_DIR.'/autoload.php'))
    include SM_VENDOR_DIR.'/autoload.php';

$loader = new Psr4AutoloaderClass;
$loader->addNamespace('websm', __DIR__);

$modules = include SM_MODULES_DIR.'/modules.php';

foreach($modules as $namespace => $directory)
    $loader->addNamespace($namespace, $directory);

$loader->register();
