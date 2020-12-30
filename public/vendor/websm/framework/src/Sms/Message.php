<?php

namespace Websm\Framework\Sms;

use Websm\Framework\Sms\Exceptions\InvalidArgumentException;

class Message implements MessageInterface {

    private $phone;
    private $body;
    private $alias;

    public function __construct($phone, $body, $alias = 'websm.io') {

        if (!preg_match('/^\d{11}$/', $phone))
            throw new InvalidArgumentException('Phone format error.');

        if (!preg_match('/^[\w$@:)(\-;_.]{1,11}$/u', $alias))
            throw new InvalidArgumentException('Alias format error.');

        $this->phone = $phone;
        $this->body = $body;
        $this->alias = $alias;

    }

    public function getAlias() {

        return $this->alias;

    }

    public function getPhone() {

        return $this->phone;

    }

    public function getBody() {

        return $this->body;

    }

}
