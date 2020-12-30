#!/usr/bin/env php
<?php

include __DIR__.'/../../init.php';

use Websm\Framework\MessageQueue\BeanstalkQueue;
use Websm\Framework\MessageQueue\Exceptions;

use Pheanstalk\Pheanstalk;

$queue = new BeanstalkQueue(new Pheanstalk('127.0.0.1'), 'example');

while (true) {

    try {

        $task = $queue->shift();

    } catch (Exceptions\ConnectionException $e) {

        echo "Connection refused\r\n";
        sleep(5);
        continue;

    }

    print_r($task);

    try {

        print_r($task->message);


    } catch (Exception $e) {

        echo $e->getMessage();
        $queue->pushTask($task);

    }

}
