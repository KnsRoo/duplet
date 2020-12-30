<?php

namespace API\Catalog\V2\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class OrderGroups {

    const ORDERS = [
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    const ORDER_FIELDS = [ 
        'code' => 'code',
        'title' => 'title',
        'sort' => 'sort',
    ];

    public static function filter($qb, $order) {

        $res = [];

        if (is_object($order)) {

            foreach($order as $key => $value) {

                if ($key !== 'props' && array_key_exists($key, self::ORDER_FIELDS)) {

                    $key = self::ORDER_FIELDS[$key];
                    $orderVal = 'ASC';
                    if (array_key_exists($value, self::ORDERS)) {
                        $orderVal = self::ORDERS[$value];
                    }

                    $sql = "`${key}` ${orderVal}";
                    $res[] = $sql;
                }
            }

            $qb = $qb->order($res);

        } else $qb = $qb->order(['`sort` ASC']);

        return $qb;
    }
}
