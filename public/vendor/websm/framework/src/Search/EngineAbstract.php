<?php

namespace Websm\Framework\Search;

abstract class EngineAbstract implements EngineInterface {

    use EngineTrait;

    abstract public function count($query);

    abstract public function getName();

}
