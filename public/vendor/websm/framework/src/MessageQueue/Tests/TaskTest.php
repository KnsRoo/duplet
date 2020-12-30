<?php

include_once __DIR__.'/../../init.php';

use PHPUnit\Framework\TestCase;

use Websm\Framework\MessageQueue\Task;
use Websm\Framework\MessageQueue\TypeEnum;

use Websm\Framework\MessageQueue\Exceptions\NotFoundException;
use Websm\Framework\MessageQueue\Exceptions\InvalidArgumentException;

class TaskTest extends TestCase {

    private function getTaskMSGTypeString() {

        $data = [
            'id' => '1',
            'message' => 'test',
        ];
        $type = new TypeEnum('STRING');
        $task = new Task($data, $type);

        return $task;

    }

    private function getTaskMSGTypeJSON() {

        $data = [
            'id' => '1',
            'message' => ['key' => 'value'],
        ];
        $type = new TypeEnum('JSON');
        $task = new Task($data, $type);

        return $task;

    }

    public function testCreateTask() {

        $data = [
            'id' => '1',
            'message' => 'test',
            'sleep' => 1000,
            'trys' => 10,
        ];

        $type = new TypeEnum('STRING');

        try {

            $task = new Task($data, $type);
            $this->assertTrue(true);

        } catch (Exception $e) {

            $this->assertTrue(false);

        }

    }

    public function testMissingData() {

        $type = new TypeEnum('STRING');

        $this->expectException(NotFoundException::class);

        $task = new Task([], $type);

    }

    public function testToString() {

        $task = $this->getTaskMSGTypeString();
        $this->assertEquals($task->__toString(), $task->toJson());

    }

    public function testGetMessageAsString() {

        $task = $this->getTaskMSGTypeString();

        $this->assertTrue(is_string($task->getMessage()));

    }

    public function testGetMessageAsJSON() {

        $task = $this->getTaskMSGTypeJSON();

        $this->assertTrue(is_object($task->getMessage()));

    }

    public function testToJSON() {

        $task = $this->getTaskMSGTypeString();

        $this->assertTrue(is_string($task->toJSON()));

    }

    public function testDecTrys() {

        $task = $this->getTaskMSGTypeString();

        $task->decTrys();
        $this->assertEquals(4, $task->trys);

    }

    public function testSleep() {

        $this->assertTrue(method_exists(Task::class, 'sleep'));

    }

}
