<?php

namespace Websm\Framework;

use Websm\Framework\NoSQL\NoSQLInterface;
use Websm\Framework\Exceptions\BaseException as Exception;

class CodeGenerator {

    private $nosql;
    private $codeLength = 6;
    private $numeric = true;

    public function __construct(
        NoSQLInterface $nosql,
        $codeLength = 6,
        $numeric = true
    ) {

        $this->nosql = $nosql;
        $this->codeLength = $codeLength;
        $this->numeric = $numeric;

    }

    public function setCodeLength($length) {

        $this->codeLength = $length;

    }

    public function numeric($status = true) {

        $this->numeric = $status;

    }

    public function create($id, $timeout = 60*5) {

        if (empty($id))
            throw new Exception('Empty value id.');

        $code = '';

        for ($i=0; $i!=$this->codeLength; $i++) $code .= rand(0, 9);

        $this->nosql->set($id, $code);
        $this->nosql->setTimeout($id, $timeout);

        return $code;

    }

    public function get($id) {

        if (empty($id))
            throw new Exception('Empty value id.');

        if ($this->nosql->exists($id))
            return $this->nosql->get($id);
        else return false;

    }

    public function exists($id) {

        if($this->nosql->exists($id))
            return $this->nosql->getTimeout($id);
        else return false;

    }

    public function compare($id, $code) {

        if (empty($id) || empty($code))
            throw new Exception('Empty value code or id.');

        if ($this->nosql->exists($id))
            return $this->nosql->get($id) == $code;

        else return false;

    }

}
