<?php

namespace Websm\Framework\Router\Request;

class Request implements \ArrayAccess {

    private $params = [];
    private $query = [];
    private $body = [];
    private $route;
    private $baseUrl;
    private $path;

    public function __construct(Array $params = []) {

        $this->query = new Query;
        $this->body = new Body;
        $this->params = new Params($params);

    }

    public function __get($key) {

        if(property_exists($this, $key))
            return $this->$key;

        $methodName = mb_convert_case('get'.$key, MB_CASE_TITLE, 'UTF-8');
        $method = [$this, $methodName];
        if(method_exists($this, $methodName))
            return $method();

    }

    public function offsetExists($offset) {

        return isset($this->params[$offset]);

    }

    public function offsetGet($offset) {

        if($this->offsetExists($offset))
            return $this->params[$offset];

        else throw new \Exception ('Offset "'.$offset.'" not exists.');

    }

    public function offsetSet($offset, $value) {

        $this->params[$offset] = $value;

    }

    public function offsetUnset($offset) {

        unset($this->params[$offset]);

    }

    public function isAjax() {

        $key = 'HTTP_X_REQUESTED_WITH';

        if (isset($_SERVER[$key]) &&
            $_SERVER[$key] == 'XMLHttpRequest') return true;

        else return false;

    }

}
