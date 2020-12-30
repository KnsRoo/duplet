<?php

$loader = require_once __DIR__.'/vendor/autoload.php';

$di = Websm\Framework\Di\Container::instance();

$di->addShared("hello", function() {
    return "world";
});

print_r($di);
die();

