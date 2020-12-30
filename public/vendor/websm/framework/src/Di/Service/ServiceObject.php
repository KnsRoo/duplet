<?php

namespace Websm\Framework\Di\Service;

use Websm\Framework\Di\Container;

class ServiceObject extends ServiceAbstract {

    public function __construct($service, Container $di = null) {

        foreach (class_implements($service) as $interface) {

            if ($di && !$di->has($interface))
                $di->add($interface, $this);

        }

        parent::__construct($service, $di);

    }


    public function build(...$argv) {

        return $this->service;

    }

}
