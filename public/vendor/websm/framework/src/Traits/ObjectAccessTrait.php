<?php

namespace Websm\Framework\Traits;

use Websm\Framework\Exceptions\NotFoundException;

trait ObjectAccessTrait {

    public function __get($key) {

        $methodName = sprintf('get%s',
            mb_convert_case($key, MB_CASE_TITLE, "UTF-8"));

        if (method_exists($this, $methodName))
            return $this->$methodName();

        elseif (isset($this->$key))
            return $this->$key;

        else throw new NotFoundException("key ${key} not found.");

    }

    public function __set($key, $value) {

        $methodName = sprintf('set%s',
            mb_convert_case($key, MB_CASE_TITLE, "UTF-8"));

        if (method_exists($this, $methodName))
            $this->$methodName($value);

        else throw new NotFoundException("method ${$methodName} not found.");

    }

}
