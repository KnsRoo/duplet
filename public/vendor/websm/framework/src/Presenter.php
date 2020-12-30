<?php

namespace Websm\Framework;

use Websm\Framework\Di;

class Presenter extends Assets {

    protected $filters = [];

    protected static $gFilters = [];

    public function render($temp = '', Array $data = []) {

        if(!file_exists($temp) || !is_file($temp))
            throw new \Exception('File not found.');

        extract($data, EXTR_REFS);
        ob_start();

        include $temp;

        return $this->pipe(ob_get_clean());

    }

    protected function inCache($file, $expiry) {

        $res  = file_exists($file);

        $res = $res && filemtime($file) > (time() - $expiry);

        return $res;

    }

    public function cacheStart($name, $expiry = 30) {

        $di = Di\Container::getLast();
        $pool = $di->get('Cache');

        $item = $pool->getItem($name);

        if ($item->isHit()) {

            echo $item->get();
            return false;

        }

        ob_start(function ($buffer) use ($item, $pool, $expiry) {

            if ($expiry) $item->expiresAfter($expiry);
            $item->set($buffer);
            $pool->save($item);
            return $buffer;

        });

        return true;

    }

    public function cacheStop() {

        echo ob_get_clean();

    }

    public static function addGFilter($filter) {

        if(!is_callable($filter))
            throw new \Exception('Filter is not callable.');

        self::$gFilters[] = $filter;

    }

    public function addFilter($filter) {

        if(!is_callable($filter))
            throw new \Exception('Filter is not callable.');

        $this->filters[] = $filter;

    }

    public function pipe($buffer = '') {

        $filters = array_merge(self::$gFilters, $this->filters);

        foreach($filters as $filter)
            call_user_func_array($filter, [&$buffer]);

        return $buffer;

    }

}
