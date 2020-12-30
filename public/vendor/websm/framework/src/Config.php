<?php

namespace Websm\Framework;

class Config{

    protected static $data = [];
    protected static $objects = [];

    public static function init(Array $config) {

        self::$data = array_merge(self::$data, $config);

    }

    public static function set($key, $value) {

        self::$data[$key] = $value;

    }

    public static function get($key) {

        if(!isset(self::$data[$key]))
            throw new \Exception('Unknown config key "'.$key.'".');

        $data = self::$data[$key];

        if(!is_array($data) || !isset($data['class']))
            return $data;

        $class = $data['class'];

        if(isset(self::$objects[$class]))
            return self::$objects[$class];

        if(!class_exists($class))
            throw new \Exception('Class "'.$class.'" not found.');

        self::$objects[$class] = new $class($data);

        return self::$objects[$class];

    }


}
