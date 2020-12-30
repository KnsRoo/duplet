<?php

namespace Websm\Framework\Path;

use ArrayAccess;
use Iterator;
use Countable;

class PathQueue implements PathQueueInterface {

    private $pool = [];
    private $position = 0;

    public function __construct(Array $items = []) {

        $this->pool = array_map(function ($item) {

            if ($item instanceof PathItemInterface)
                return $item;

            else {

                $message = 'One or more items is not PathItemInterface.';
                throw new Exception($message);

            }

        }, $items);

    }

    public function __toString() {

        return implode(' > ', $this->pool);

    }

    public function push(PathItemInterface $item) {

        $this->pool[] = $item;
        return $this;

    }

    public function pop() {

        return array_pop($this->pool);

    }

    public function unshift(PathItemInterface $item) {

        array_unshift($this->pool, $item);
        return $this;

    }

    public function shift() {

        return array_shift($this->pool);

    }

    public function reverse() {

        $this->pool = array_reverse($this->pool);
        return $this;

    }

    public function extend(PathQueueInterface $queue) {

        $this->pool = array_merge($this->pool, $queue->pool);
        return $this;

    }

    public function current() { return $this->pool[$this->position]; }

    public function key() { return $this->position; }

    public function next() { ++$this->position; }

    public function rewind() { $this->position = 0; }

    public function valid() {

        return isset($this->pool[$this->position]);

    }

}
