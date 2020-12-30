<?php

namespace Websm\Framework\Search;

trait EngineTrait {

    public function find($query, TypesEnum $returnType) {

        $methodName = "findAs${returnType}";

        if (method_exists($this, $methodName)) {

            $method = [$this, $methodName];
            return $method($query);

        } else return [];

    }

}
