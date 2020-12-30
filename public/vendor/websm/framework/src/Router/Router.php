<?php

namespace Websm\Framework\Router;

use Websm\Framework\Router\Request\Request;

use Websm\Framework\Exceptions\InvalidArgumentException;
use Websm\Framework\Exceptions\NotFoundException;

class Router {

    protected $url = '';
    protected $baseUrl = '';
    protected $queue = [];
    protected $allowed = false;
    protected $pattern;
    protected $parent;
    protected $call;

    protected static $current;
    protected static $named = [];

    public static function init($url = '') {

        if(!is_string($url))
            throw new InvalidArgumentException('$url is not string.');

        $router = self::$current = new self;
        $router->setUrl($url ?: $_SERVER['REQUEST_URI']);
        return $router;

    }

    public static function instance($name = null) {

        if($name) return self::byName($name);

        elseif(self::$current) return self::$current;

        else throw new NotFoundException('instances not found.');

    }

    public static function byName($name) {

        if(!$name)
            throw new InvalidArgumentException('$name is empty.');

        if(!is_string($name))
            throw new InvalidArgumentException('$name is not string.');

        if(!isset(self::$named[$name]))
            throw new NotFoundException('Is not defined.');

        return self::$named[$name];

    }

    public static function group() {

        return new self;

    }

    public static function normalizeUrl($url = '') {

        if(!is_string($url))
            throw new InvalidArgumentException('$url is not string.');

        $url = rawurldecode($url);
        $url = preg_replace('/\\\+/', '/', $url);
        $url = preg_replace('/^\/*(.*)$/', '/$1', $url);
        $url = preg_replace('/(\?|\#).*$/', '', $url);

        return $url;

    }

    public function setUrl($url = '') {

        if(!is_string($url))
            throw new InvalidArgumentException('$url is not string.');

        $this->url = self::normalizeUrl($url);

    }

    public function add($method = 'GET', $pattern = '', $call = null, Array $options = []) {

        $request = isset($_POST['_method'])
            ? $_POST['_method']
            : $_SERVER['REQUEST_METHOD'];

        $allowed = !!preg_match('/^('.$method.')$/i', $request);
        return $this->addAll($pattern, $call, $options, $allowed);

    }

    public function addGet($pattern = '', $call = null, Array $options = []) {

        return $this->add('GET', $pattern, $call, $options);

    }

    public function addPost($pattern = '', $call = null, Array $options = []) {

        return $this->add('POST', $pattern, $call, $options);

    }

    public function addAjax($pattern = '', $call = null, Array $options = []) {

        $allowed = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        return $this->addAll($pattern, $call, $options, $allowed);

    }

    public function addPut($pattern = '', $call = null, Array $options = []) {

        return $this->add('PUT', $pattern, $call, $options);

    }

    public function addDelete($pattern = '', $call = null, Array $options = []) {

        return $this->add('DELETE', $pattern, $call, $options);

    }

    public function addAll($pattern, $call = null, Array $options = [], $allowed = true) {

        if(is_array($pattern)) list($pattern, $name) = $pattern;

        $route = new Route;
        $route->pattern = new Pattern($pattern, $options);
        $route->allowed = !!$allowed;
        $route->parent = $this;
        $route->call = $call;

        if(isset($name)) {
            self::$named[$name] = $this->queue[$name] = $route;
        }
        else $this->queue[] = $route;

        return $route;

    }

    public function route($pattern = '/', Array $options = []) {

        $route = new Route;
        $this->mount($pattern, $route, $options);

        return $route;

    }

    public function mount($pattern = '/', Router $router, Array $options = []) {

        $options['end'] = false;
        $router->pattern = new Pattern($pattern, $options);
        $router->parent = $this;
        $router->allowed = true;
        $this->queue[] = $router;

        return $router;

    }

    public function where($vars, $exp = null) {

        !is_array($vars) && $vars = [$vars => $exp];
        $this->pattern->setExpForKeys($vars);

        return $this;

    }

    public function getParent() { return $this->parent; }

    public function getPath(Array $replaces = [], $pretty = false) {

        $path = '';

        if($this->pattern)
            $path = $this->pattern->toPath($replaces, $pretty);

        return $path;

    }

    public function getBasePath(Array $replaces = [], $pretty = false) {

        $path = '';

        if($this->parent)
            $path = $this->parent->getAbsolutePath($replaces, $pretty);

        return $path;

    }

    public function getAbsolutePath(Array $replaces = [], $pretty = false) {

        $path = '';

        if($this->pattern) {

            $path = $this->pattern->toPath($replaces, $pretty);
            $path = $path == '/' ? '' : $path;

        }

        if ($this->parent) {

            $path = $this->parent->getAbsolutePath($replaces, $pretty).$path;

        }

        return preg_replace('/\/{2,}/', '/', $path);

    }

    public static function getOrigin() {

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        return $origin;
    }

    public function getURL(Array $replaces = [], $pretty = false) {

        return self::getOrigin() . $this->getAbsolutePath($replaces, $pretty);
    }

    public function start() {

        $route = array_shift($this->queue);
        if(!$route) return $this->parent ? $this->parent->start() : false;

        $pattern = $route->pattern;

        if($route->allowed && $req = $pattern->match($this->url)) {

            $req = new Request($req);

            $route->baseUrl = $this->url;
            $route->setUrl(preg_replace($pattern, '', $this->url));

            self::$current = $route;

            if(is_callable($route->call))
                call_user_func(
                    $route->call,
                    $req, [$route, 'start']
                );

            else $route->start();

        }
        else $this->start();

    }

}
