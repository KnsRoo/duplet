<?php

namespace Websm\Framework\Router;

class Route extends Router {

    public function addAll($pattern, $call = null, Array $options = [], $allowed = true) {

        if(is_callable($pattern)) {
            $call = $pattern;
            $pattern = '/';
        }

        if(!is_callable($call))
            throw new \Exception('Missing handler');

        parent::addAll($pattern, $call, $options, $allowed);

        return $this;

    }

    public function setName($name) {

        if(!$name)
            throw new \Exception('$name is empty.');

        if(!is_string($name))
            throw new \Exception('$name is not string.');

        self::$named[$name] = $this;
        return $this;

    }

    public function withAjax() {

        if($this->allowed) {

            $this->allowed = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and
                $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

        }

        return $this;

    }

    public function withNotAjax() {

        if($this->allowed) {

            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                $this->allowed = true;

            elseif ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
                $this->allowed = true;

            else $this->allowed = false;

        }

        return $this;

    }

    public function via($method = 'GET') {

        if($this->allowed) {

            $request = isset($_POST['_method'])
                ? $_POST['_method']
                : $_SERVER['REQUEST_METHOD'];

            $this->allowed = !!preg_match('/^('.$method.')$/i', $request);

        }

        return $this;

    }

    public function compare(self $route) {

        return $this === $route;

    }

}
