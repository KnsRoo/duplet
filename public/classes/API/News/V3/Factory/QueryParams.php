<?php

namespace API\News\V3\Factory;

class QueryParams {

    const ORDER_FIELDS = [ 
        'title' => 'title',
        'creationDate' => 'date',
    ];

    const ORDER_TYPES = [ 
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    public static function getOffset($default = 0) {

        $offset = isset($_GET['offset']) ? (Integer)$_GET['offset'] : $default;
        return $offset;

    }

    public static function getLimit($default = 30, $max = 1000) {

        $limit = isset($_GET['limit']) ? (Integer)$_GET['limit'] : $default;
        $limit = min($limit, $max);
        return $limit;

    }

    public static function getOrder() {

        $orderRaw = isset($_GET['order']) ? $_GET['order'] : [];

        $order = [];

        if (!is_array($orderRaw))
            $order = [ 'creationDate' => 'desc' ];
        else {

            foreach($orderRaw as $key => $value) {

                if (array_key_exists($key, self::ORDER_FIELDS)) {

                    if (array_key_exists($value, self::ORDER_TYPES)) {
                        $order[self::ORDER_FIELDS[$key]] = self::ORDER_TYPES[$value];
                    } else {
                        $order[self::ORDER_FIELDS[$key]] = 'ASC';
                    }
                }
            }
        }

        $orderFmt = [];
        foreach($order as $key => $value)
            $orderFmt[] = '`' . $key . '` ' . $value; 

        return $orderFmt;
    }
}
