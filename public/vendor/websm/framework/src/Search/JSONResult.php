<?php

namespace Websm\Framework\Search;

class JSONResult {

    public $title = '';
    public $about = '';
    public $ref = '';
    public $picture = '';

    public function __toString() {

        return json_encode($this);

    }

    public function bind(Array &$data) {

        foreach ($data as $key => $value) {

            $this->$key = $value;

        }

    }

}
