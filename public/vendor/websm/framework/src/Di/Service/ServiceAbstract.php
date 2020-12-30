<?php

namespace Websm\Framework\Di\Service;

use Websm\Framework\Di\Container;

abstract class ServiceAbstract implements ServiceInterface {

    protected $service;
    protected $container;
    protected $shared = false;
    protected $sharedData;

    public function __construct($service, Container $container = null) {

        $this->service = $service;
        $this->container = $container;

    }

    public function shared($status = true) {

        $this->shared = !!$status;
        return $this;

    }

    public function isShared() { return $this->shared; }

    public function setDi(Container $di) { $this->container = $di; }

    public function getDi() { return $this->container; }

    abstract public function build(...$argv);

    public function buildShared(...$argv) {

        if ($this->sharedData === null) {

            $data = $this->build(...$argv);
            $this->sharedData = $data;

        }

        return $this->sharedData;

    }

}
