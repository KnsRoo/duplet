<?php

namespace Websm\Framework\MessageQueue;

use Pheanstalk\Pheanstalk;
use Pheanstalk\Exception\ConnectionException;

use Websm\Framework\Exceptions\NotFoundException;

use Websm\Framework\MessageQueue\Exceptions\ConnectionException as QConnectionException;
use Websm\Framework\MessageQueue\Exceptions\TypeErrorException;

/**
 * BeanstalkQueue 
 * 
 * @author Igor Bykov <con29rus@live.ru> 
 *
 */
class BeanstalkQueue implements QueueInterface {

    private $pheanstalk;
    private $name;

    public function __construct(Pheanstalk $pheanstalk, $name = 'default') {

        $this->pheanstalk = $pheanstalk;
        $this->name = $name;

        $pheanstalk->useTube($name);

    }

    /**
     * pushTask 
     *
     * @throws ConnectionException Если было потеряно соединение.
     * @see QueueInterface::pushTask 
     */
    public function pushTask(Task $task) {

        if (!$task->trys) return false;

        $pheanstalk = $this->pheanstalk;

        /* if ($pheanstalk->getConnection()->isServiceListening()) { */

        $pheanstalk->put($task->toJSON());

        /* } else { */

        /*     throw new QConnectionException('beanstalk gone away.'); */

        /* } */

        return true;

    }

    /**
     * push 
     * 
     * @see QueueInterface::push
     * @throws ConnectionException Если было потеряно соединение.
     */
    public function push($message, $sleep = 1000, $trys = 5) {

        $pheanstalk = $this->pheanstalk;

        if (is_array($message) || is_object($message)) {

            $type = new TypeEnum('JSON');

        } else {

            $type = new TypeEnum('STRING');

        }

        $data = [];
        $data['message'] = $message;
        $data['sleep'] = $sleep;
        $data['trys'] = $trys;

        $task = new Task($data, $type);

        return $this->pushTask($task);

    }

    /**
     * shift 
     * 
     * @see QueueInterface::shift
     * @throws ConnectionException Если было потеряно соединение.
     */
    public function shift() {

        $pheanstalk = $this->pheanstalk;

        try {

            $job = $pheanstalk
                ->watch($this->name)
                ->ignore('default')
                ->reserve();

            $pheanstalk->delete($job);

        } catch (ConnectionException $e) {

            throw new QConnectionException('beanstalk gone away.');

        }

        $data = json_decode($job->getData(), true);

        if ($data === null) {

            throw new TypeErrorException('Message type is not JSON.');

        }

        try {

            $type = TypeEnum::valueOf($data['type']);

        } catch (NotFoundException $e) {

            throw new TypeErrorException('Message format invalid.');

        }

        $task = new Task($data, $type);
        $task->decTrys();
        $task->sleep();

        return $task;

    }

}
