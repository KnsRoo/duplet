<?php

namespace Websm\Framework\Exceptions;

class HTTP extends \Exception {

    private $httpcode;

    public function __construct($message, $httpcode = 500) {

        parent::__construct($message);
        $this->httpcode = $httpcode;
    }

    public function getHttpCode() {

        return $this->httpcode;
    }

}
