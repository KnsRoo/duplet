<?php

include_once __DIR__.'/../../init.php';

use PHPUnit\Framework\TestCase;
use Pheanstalk\Pheanstalk;

use Websm\Framework\MessageQueue\BeanstalkQueue;
use Websm\Framework\MessageQueue\TypeEnum;
use Websm\Framework\MessageQueue\Task;

use Websm\Framework\MessageQueue\Exceptions\NotFoundException;
use Websm\Framework\MessageQueue\Exceptions\InvalidArgumentException;

class BeanstalkQueueTest extends TestCase {

    private $connection;
    private $queueName;

    private function getQueue() {

        return new BeanstalkQueue($this->connection, $this->queueName);

    }

    private function getTask($trys = 5) {

        $type = new TypeEnum('STRING');
        $task = new Task([
            'message' => 'test',
            'trys' => $trys,
            'sleep' => 200,
        ], $type);

        return $task;

    }

    public function __construct(...$argv) {

        $this->queueName = 'test_'.uniqid();
        $this->connection = new Pheanstalk('127.0.0.1');
        parent::__construct(...$argv);

    }

    public function testCreateQueue() {

        try {

            $queue = new BeanstalkQueue($this->connection, $this->queueName);
            $this->assertTrue(true);

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testPushTask() {

        $task = $this->getTask();
        $queue = $this->getQueue();

        $result = $queue->pushTask($task);
        $queue->shift();
        $this->assertTrue($result);

        $task = $this->getTask(0);

        $result = $queue->pushTask($task);
        $this->assertFalse($result);

    }

    public function testPushStringMessage() {

        $queue = $this->getQueue();

        try {

            $queue->push('test');
            $task = $queue->shift();
            $this->assertTrue(true);

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testPushJSONMessage() {

        $queue = $this->getQueue();

        try {

            $data = ['test_key' => 'test_value'];
            $queue->push($data, 0);
            $queue->push((Object)$data, 0);

            $queue->shift();
            $queue->shift();

            $this->assertTrue(true);

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testShiftTask() {

        $queue = $this->getQueue();
        $pushedTask = $this->getTask();

        $queue->pushTask($pushedTask);
        $pushedTask->decTrys();

        $shiftedTask = $queue->shift();

        $result = $pushedTask == $shiftedTask;

        $this->assertTrue($result);

    }

}
