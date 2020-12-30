#!/usr/bin/env php
<?php

include __DIR__.'/../../init.php';

use Websm\Framework\MessageQueue\BeanstalkQueue;

use Pheanstalk\Pheanstalk;

$queue = new BeanstalkQueue(new Pheanstalk('127.0.0.1'), 'example');

$queue->push(['key' => 'value'], 0);
$queue->push(['key' => 'value'], 0);
$queue->push(['key' => 'value'], 0);
$queue->push(['key' => 'value'], 0);
