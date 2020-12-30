<?php

namespace Websm\Framework\NoSQL;

use Redis;

class RedisConnection implements NoSQLInterface {

    private $redis;

    public function __construct($host = '127.0.0.1', $port = 6379) {

        $this->redis = new Redis();
        $this->redis->connect($host, $port);

    }

    public function set($key, $value) {

        return $this->redis->set($key, $value);

    }

    public function delete($keys) {

        if(!is_array($keys)) $keys = [$keys];
        return $this->redis->del($keys);

    }

    public function get($key) {

        return $this->redis->get($key);

    }

    public function setTimeout($key, $sec) {

        return $this->redis->expire($key, $sec);

    }

    public function getTimeout($key) {

        return $this->redis->ttl($key);

    }

    public function exists($key) {

        return $this->redis->exists($key);

    }

    public function keys($pattern = '*') {

        return $this->redis->keys($pattern);

    }

}
