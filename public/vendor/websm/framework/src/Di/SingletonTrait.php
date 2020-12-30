<?php

namespace Websm\Framework\Di;

use Exceptions\DiBaseException as Exception;

trait SingletonTrait {

    private static $instance;

    final private function __construct() {

    }

    final public function __clone() {

        throw new Exception('Cloning is forbidden.');

    }

    final public function __wakeup() {

        if (self::$instance) {

            throw new Exception('Instance already exists.');

        } else {

            self::$instance = $this;

        }

    }

    public static function instance() {

        if (is_null(self::$instance)) {

            return self::$instance = new self;

        } else {

            return self::$instance;

        }

    }

}

