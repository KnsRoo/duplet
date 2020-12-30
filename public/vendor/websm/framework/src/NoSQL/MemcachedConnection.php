<?php

namespace Websm\Framework\NoSQL;

use Websm\Framework\Exceptions\BaseException as Exception;
use Memcached;

class MemcachedConnection implements NoSQLInterface {

    private $connection;

    public function __construct($host = '127.0.0.1', $port = 11211) {

        $this->connection = new Memcached();
        $this->connection
            ->addServer($host, $port);

    }

    public function set($key, $value) {

        return $this->connection->set($key, $value);

    }

    public function delete($keys) {

        if(!is_array($keys)) $keys = [$keys];
        return $this->connection->deleteMulti($keys);

    }

    public function get($key) {

        return $this->connection->get($key);

    }

    public function setTimeout($key, $sec) {

        throw new Exception('Not available.');

    }

    public function getTimeout($key) {

        throw new Exception('Not available.');

    }

    public function exists($key) {

        $result = $this->connection->get($key);
        return $result === false ? false : true;

    }

    public function keys($pattern = '*') {

        return $this->connection->getAllKeys($pattern);

    }

}
