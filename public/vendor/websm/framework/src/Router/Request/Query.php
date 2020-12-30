<?php

namespace Websm\Framework\Router\Request;

class Query implements \ArrayAccess {

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

        $this->data = $data ?: $_GET;

    }

    public function __toString() { return $this->build(); }

    public function build($pretty = false) {

        $params = [];
        foreach($this->data as $key => $value) {

            $value = $pretty ? $value : urlencode($value);
            $key = $pretty ? $key : urlencode($key);
            $params[] = $key.'='.$value;

        }

        $string = '?'.implode('&', $params);

        return $string;

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
