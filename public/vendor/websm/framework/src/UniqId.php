<?php

namespace Websm\Framework;

class UniqId {

    private $data = '';
    private $more = true;

    public function __construct($more = true) {

        $this->more = $more;
        $this->data = $this->generate();

    }

    private function generate() {

        $ret = uniqid('', $this->more);
        $ret = mb_strtoupper($ret, 'UTF-8');

        if ($this->more) {

            $ret = mb_substr($ret, 0, 12, 'UTF-8');
            $ret = preg_replace('/^(.{4})(.{4})(.{4})/', '$1-$2-$3', $ret);

        } else {

            $ret = mb_substr($ret, -8, null, 'UTF-8');
            $ret = preg_replace('/^(.{4})/', '$1-', $ret);

        }

        return $ret;

    }

    public function __toString() {

        return $this->data;

    }

}
