<?php

namespace Websm\Framework;

use Traversable;

class Types {

    public static function isIterable($data) {

        return (is_array($data) || $data instanceof Traversable);

    }

}
