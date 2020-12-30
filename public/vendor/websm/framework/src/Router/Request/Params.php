<?php

namespace Websm\Framework\Router\Request;

class Params implements \ArrayAccess {

    private $data = [];

    public function __get($key) {

        if(isset($this->data[$key]))
            return $this->data[$key];

        else throw new \Exception('Not found key "'.$key.'".');

    }

    public function __set($key, $value) {

        $this->data[$key] = $value;

    }

    public function __construct(Array $data = []) {

        $this->data = $data;

    }

    public function offsetExists($offset) {

        return isset($this->data[$offset]);

    }

    public function offsetGet($offset) {

        if($this->offsetExists($offset))
            return $this->data[$offset];

        else throw new \Exception ('Offset "'.$offset.'" not exists.');

    }

    public function offsetSet($offset, $value) {

        $this->data[$offset] = $value;

    }

    public function offsetUnset($offset) {

        unset($this->data[$offset]);

    }

}
