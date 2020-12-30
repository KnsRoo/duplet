<?php

namespace Back\Orders\API\V1\Factory;

class QueryParams {

    public static function getOffset($default = 0) {

        $offset = isset($_GET['offset']) ? (Integer)$_GET['offset'] : $default;
        return $offset;

    }

    public static function getLimit($default = 30, $max = 1000) {

        $limit = isset($_GET['limit']) ? (Integer)$_GET['limit'] : $default;
        $limit = min($limit, $max);
        return $limit;

    }

    public static function getEmbed() {

        $embed = isset($_GET['embed']) ? $_GET['embed'] : [];
        if (!is_array($embed))
            $embed = [ $embed ];

        return $embed;
    }
}
