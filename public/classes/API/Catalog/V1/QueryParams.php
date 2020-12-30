<?php

namespace API\Catalog\V1;

class QueryParams {

    const ORDER_PRODUCT_FIELDS = [ 
        'title' => 'title',
        'creationDate' => 'date',
        'code' => 'code',
        'price' => 'price',
        'sort' => 'sort',
    ];

    const ORDER_GROUP_FIELDS = [ 
        'title' => 'title',
        'sort' => 'sort',
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

    public static function getOrderGroups() {

        $orderRaw = isset($_GET['order']) ? $_GET['order'] : [];

        $order = [];

        if (!is_array($orderRaw)) {
            $order = [ 'sort' => 'ASC' ];
        } else {

            foreach($orderRaw as $key => $value) {

                if (array_key_exists($key, self::ORDER_GROUP_FIELDS)) {

                    if (array_key_exists($value, self::ORDER_TYPES)) {
                        $order[self::ORDER_GROUP_FIELDS[$key]] = self::ORDER_TYPES[$value];
                    } else {
                        $order[self::ORDER_GROUP_FIELDS[$key]] = 'ASC';
                    }
                }
            }
        }

        $orderFmt = [];
        foreach($order as $key => $value)
            $orderFmt[] = '`' . $key . '` ' . $value; 

        return $orderFmt;
    }

    public static function getOrderProducts() {

        $orderRaw = isset($_GET['order']) ? $_GET['order'] : [];

        $order = [];

        if (!is_array($orderRaw)) {
            $order = [ 'sort' => 'ASC' ];
        } else {

            foreach($orderRaw as $key => $value) {

                if (array_key_exists($key, self::ORDER_PRODUCT_FIELDS)) {

                    if (array_key_exists($value, self::ORDER_TYPES)) {
                        $order[self::ORDER_PRODUCT_FIELDS[$key]] = self::ORDER_TYPES[$value];
                    } else {
                        $order[self::ORDER_PRODUCT_FIELDS[$key]] = 'ASC';
                    }
                }
            }
        }

        $orderFmt = [];
        foreach($order as $key => $value)
            $orderFmt[] = '`' . $key . '` ' . $value; 

        return $orderFmt;
    }

    public static function getTags() {

        $tags = isset($_GET['tags']) ? $_GET['tags'] : [];
        if (!is_array($tags))
            $tags = [];

        return $tags;

    }

    public static function getProps() {

        $props = isset($_GET['props']) ? $_GET['props'] : '';
        if (is_array($props)) $props = '';
        $props = json_decode($props);
        if(!is_array($props)) $props = [];

        return $props;
    }


}
