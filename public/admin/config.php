<?php

define('_PWD', '/admin');
define('_DUMPS', __DIR__.'/dumps');
define('_CSS', __DIR__.'/css');
define('_ICONS', __DIR__.'/icons');
define('_JS', __DIR__.'/js');
define('_TEMP', __DIR__.'/tempaltes');
define('_PL', __DIR__.'/pl');
define('_SHARED_DATA', __DIR__.'/../data');
define('_CORE', __DIR__.'/../classes/Core');
define('_BACK', __DIR__.'/../classes/Back');
define('_FRONT', __DIR__.'/../classes/Front');

return [
    'db' => [
        'class' => '\Websm\Framework\Db\Connection',
        'dsn' => 'mysql:host=m.0.mysql.websm.io;port=3306;dbname=db_duplet.shop;charset=utf8',
        'username' => 'duplet_admin',
        'password' => '8hj9ypHcAA28Q4ZxKqZwWj2P5D8yaKRh'
    ],
    'files' => [
        'class' => '\Back\Files\Connection',
        'dsn' => 'sqlite:'._SHARED_DATA.'/data.db',
    ],
];
