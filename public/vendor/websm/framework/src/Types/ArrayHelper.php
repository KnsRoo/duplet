<?php

namespace Websm\Framework\Types;

use ArrayAccess;
use Iterator;
use Countable;

use Websm\Framework\Exceptions\Base as Exception;
use Websm\Framework\Exceptions\NotFoundException;

class ArrayHelper implements ArrayAccess, Iterator, Countable {

    private $pool = [];
    private $position = 0;

    public function __construct(Array $pool = []) {

        $this->position = 0;
        $this->pool = $pool;

    }

    public function &__get($key) {

        if (isset($this->pool[$key]))
            return $this->pool[$key];

        else throw new NotFoundException("Not found key ${key}.");

    }

    public function __set($key, $value) {

        $this->pool[$key] = $value;

    }

    public function __isset($key) {

        return isset($this->pool[$key]);

    }

    public function __unset($key) {

        unset($this->pool[$key]);

    }

    public function rewind() { $this->position = 0; }

    public function current() { return $this->pool[$this->position]; }

    public function key() { return $this->position; }

    public function next() { ++$this->position; }

    public function valid() {

        return isset($this->pool[$this->position]);

    }

    public function offsetSet($offset, $value) {

        if (is_null($offset)) {

            $this->pool[] = $value;

        } else {

            $this->pool[$offset] = $value;

        }

    }

    public function offsetExists($offset) {

        return isset($this->pool[$offset]);

    }

    public function offsetUnset($offset) { unset($this->pool[$offset]); }

    public function offsetGet($offset) {

        return isset($this->pool[$offset]) ? $this->pool[$offset] : null;

    }

    public function toArray() { return $this->pool; }

    public function collectValByKey($key) {

        $result = new self;

        foreach ($this->pool as $poolElement) {

            if (is_object($poolElement) && property_exists($poolElement, $key))
                $result[] = $poolElement->$key;

            elseif (is_array($poolElement) && key_exists($key, $poolElement))
                $result[] = $poolElement[$key];

        }

        return $result;

    }

    public function column($key = null, $index = null) {

        $result = array_column($this->pool, $key, $index);
        return new self($result);

    }

    public function isEmpty() { return empty($this->pool); }

    public function map(callable $callback) {

        return array_map($callback, $this->pool);

    }

    public function filter(callable $callback, $flag = ARRAY_FILTER_USE_KEY) {

        return array_filter($this->pool, $callback, $flag);

    }

    public function push(...$data) {

        return array_push($this->pool, ...$data);

    }

    public function pop() {

        return array_pop($this->pool);

    }

    public function shift() {

        return array_shift($this->pool);

    }

    public function getNotEmpty() {

        foreach ($this->pool as &$value) {

            if (!$value) continue;

            return $value;

        }

    }

    public function count() { return count($this->pool); }

    public function chunk(...$args) {

        $chunked = array_chunk($this->pool, ...$args);

        $chunked = array_map(function ($value) {

            return new self($value);

        }, $chunked);

        $result = new self($chunked);
        return $result;

    }

}
