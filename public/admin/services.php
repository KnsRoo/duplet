<?php

use Websm\Framework\Di;
use Websm\Framework\MessageQueue\BeanstalkQueue;
use Pheanstalk\Pheanstalk;

$container = Di\Container::instance();

$container->add('search', function ($di) {

    $search = new Websm\Framework\Search\Service;
    $search->addEngine(new \Back\Catalog\Engine\Search);

    return $search;

});

$container->addShared('video-queue', function ($container) {

    $queue = new BeanstalkQueue(new Pheanstalk('127.0.0.1'), 'video');
    return $queue;

});

$container->addShared('crypt', new Websm\Framework\Crypt('8CcN2uvREX6GoengEncQzI6g1bAtchr3sHdD0Rsv'));

$container->addShared('sms-gate', new \Websm\Framework\Sms\SmsSenderClient(
    "http://smssender.websm.io:7000"
));
