<?php

namespace Websm\Framework\MessageQueue;

use Websm\Framework\MessageQueue\Exceptions\NotFoundException;
use Websm\Framework\MessageQueue\Exceptions\InvalidArgumentException;
use Websm\Framework\MessageQueue\Exceptions\QueueBaseException;

use Websm\Framework\Traits\ObjectAccessTrait;

class Task implements TaskInterface {

    use ObjectAccessTrait;

    private $message;
    private $sleep = 1000;
    private $trys = 5;
    private $type;

    public function __construct(Array $data, TypeEnum $type) {


        $this->type = $type->get();
        extract($data);

        $this->setMessage($message);

        if (isset($sleep)) {

            if (!is_numeric($sleep))
                throw new InvalidArgumentException('key "sleep" not numeric.');

            $this->sleep = (double)$sleep;

        }

        if (isset($trys)) {

            if (!is_numeric($trys))
                throw new InvalidArgumentException('key "trys" not numeric.');

            $this->trys = $trys;

        }

    }

    private function setMessage(&$message) {

        if (!isset($message) || !$message)
            throw new NotFoundException('key "message" is required.');

        if ($this->type === TypeEnum::JSON && 
            (is_object($message) || is_array($message))) {

            $this->message = json_encode($message);

        } else {

            $this->message = (String)$message;

        }

    }

    /**
     * __toString 
     *
     * Для перевода в строку
     * 
     * @access public
     * @return string
     * @see toJSON
     */
    public function __toString() {

        return $this->toJSON();

    }

    public function getMessage() {

        switch ($this->type) {

            case TypeEnum::STRING:

                return $this->message;
                break;

            case TypeEnum::JSON:

                return json_decode($this->message);
                break;

            default:

                return $this->message;
                break;

        }

    }

    public function toJSON() {

        $data = get_object_vars($this);
        return json_encode($data);

    }

    public function decTrys() {

        if ($this->trys) $this->trys--;

    }

    public function sleep() {

        if ($this->sleep) sleep($this->sleep / 1000);

    }

}
