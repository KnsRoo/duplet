<?php

namespace API\Catalog\V2\Factory;

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

    public static function getOrder() {

        if (!isset($_GET['order']) || !is_string($_GET['order']))
            $_GET['order'] = '';

        $order = (Object)json_decode($_GET['order'] ?? null);
        return $order;
    }

    public static function getEasyOrder() {

        if (!isset($_GET['order']) || !is_string($_GET['order']))
            $_GET['order'] = null;

        return $_GET['order'];
    }

    public static function getSort() {

        if (!isset($_GET['sort']) || !is_string($_GET['sort']))
            $_GET['sort'] = null;

        return $_GET['sort'];
    }

    public static function getTags() {

        if (!isset($_GET['tags']) || !is_string($_GET['tags']))
            $_GET['tags'] = '';

        $tags = (Object)json_decode($_GET['tags'] ?? null);
        return $tags;
    }

    public static function getProps() {

        if (!isset($_GET['props']) || !is_string($_GET['props']))
            $_GET['props'] = '';

        $props = (Object)json_decode($_GET['props'] ?? null);
        return $props;
    }

    public static function getQuery() {

        if (!isset($_GET['query']) || !is_string($_GET['query']))
            $_GET['query'] = null;

        return $_GET['query'];
    }

    public static function getCategory() {

        if (!isset($_GET['category']) || !is_string($_GET['category']))
            $_GET['category'] = null;

        return $_GET['category'];
    }
}
