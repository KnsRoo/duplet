<?php

namespace Websm\Framework\Di\Service;

class ServiceAny extends ServiceAbstract {

    public function build(...$argv) {

        return $this->service;

    }

}
