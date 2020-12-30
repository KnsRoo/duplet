<?php

namespace Websm\Framework\Di\Service;

class ServiceClosure extends ServiceAbstract {

    public function setReturnType($type) {

        $di = $this->container;

        if (class_exists($type) && $di) {

            $di->add($type, $this);

            foreach (class_implements($type) as $interface) {

                if (!$di->has($interface))
                    $di->add($interface, $this);

            }

        }

        if (interface_exists($type) && $di) {

            $di->add($type, $this);

        }

        return $this;

    }

    public function build(...$argv) {

        $service = $this->service;
        return $service($this->container, ...$argv);

    }

}
