<?php

namespace Websm\Framework\Path;

class PathItem implements PathItemInterface {

    private $title;
    private $ref;

    public function __construct($title, $ref) {

        $this->title = $title;
        $this->ref = $ref;

    }

    public function __toString() {

        return $this->title;

    }

    public function getTitle() {

        return $this->title;

    }

    public function getRef() {

        return $this->ref;

    }

}
