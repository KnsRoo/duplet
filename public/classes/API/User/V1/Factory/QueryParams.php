<?php

namespace API\User\V1\Factory;

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

        $embed = &$_GET['embed'];

        if (!is_array($embed))
            $embed = [ $embed ];

        return $embed;
    }

    public static function getOrder() {

        if (!isset($_GET['order']) || !is_string($_GET['order']))
            $_GET['order'] = '';

        $order = (Object)json_decode($_GET['order'] ?? null);
        return $order;
    }

    public static function getType() {

        $type = isset($_GET['type']) ? "'".(String)$_GET['type']."'" : null;
        return $type;
    }
}
