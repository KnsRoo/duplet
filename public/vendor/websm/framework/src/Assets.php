<?php

namespace Websm\Framework;

class Assets {

    protected $current = '';

    protected static $collections = [
        '_js' => [],
        '_css' => [],
    ];

    public function collection($name = '') {

        if(!is_string($name))
            throw new \Exception('$name is not string.');

        $assets = new self;
        $assets->current = $name;
        return $assets;

    }

    public function addJs($file = '') {

        if(!is_string($file))
            throw new \Exception('$file is not string.');

        $collection = $this->current.'_js';

        self::$collections[$collection][] = $file;
        return $this;

    }

    public function addCss($file = '') {

        if(!is_string($file))
            throw new \Exception('$file is not string.');

        $collection = $this->current.'_css';

        self::$collections[$collection][] = $file;
        return $this;

    }

    public function outJs($collection = '') {

        if(!is_string($collection))
            throw new \Exception('$collection is not string.');

        $collection .= '_js';

        if(!isset(self::$collections[$collection]))
            return null;

        $ret = '';
        foreach(self::$collections[$collection] as $file) {
            $ret .= '<script type="text/javascript" src="'.$file.'"></script>';
        }

        return $ret;

    }

    public function outCss($collection = '') {

        if(!is_string($collection))
            throw new \Exception('$collection is not string.');

        $collection .= '_css';

        if(!isset(self::$collections[$collection]))
            return null;

        $ret = '';
        foreach(self::$collections[$collection] as $file) {
            $ret .= '<link rel="stylesheet" href="'.$file.'" />';
        }

        return $ret;

    }

}
