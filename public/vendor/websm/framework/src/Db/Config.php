<?php

namespace Websm\Framework\Db;

class Config {

    protected static $data = [];
    protected static $schema = ['class' => null];
    protected static $objects = [];

    public static function init(Array $config){
        self::$data = $config;
    }

    public static function get($key){

        if(!isset(self::$data[$key]))
            throw new \Exception('Unknown config key "'.$key.'".');

        $data = array_merge(self::$schema, self::$data[$key]);

        $class = &$data['class'];

        if(!$class) return $data;

        elseif(isset(self::$objects[$class]))
            return self::$objects[$class];

        elseif($data['class'] && class_exists($data['class'])) {
            self::$objects[$class] = new $class($data);
            return self::$objects[$class];
        }

        else throw new \Exception('Class "'.$class.'" not found.');
    }
}
