<?php

namespace API\User\V1\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class OrderOrders {

    const ORDERS = [
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    public static function filter($qb, $order) {

        $res = [];

        if (is_object($order) && !empty((Array)$order)) {

            foreach($order as $key => $value) {

                if (is_array($value)) {

                    foreach($value as $field) {
                        if (property_exists($field, 'path') && is_array($field->path) && property_exists($field, 'val') && is_string($field->val)) {

                            $path = $field->path;
                            $val = $field->val;
                            $pathStr = '$';
                            foreach ($path as $item)
                                $pathStr .= ".\"${item}\"";

                            $orderVal = 'ASC';
                            if (array_key_exists($val, self::ORDERS)) {
                                $orderVal = self::ORDERS[$val];
                            }

                            $sql = "JSON_EXTRACT(`props`, '${pathStr}') ${orderVal}";
                            $res[] = $sql;
                        }
                    }
                }
            }

            $qb = $qb->order($res);

        } else $qb = $qb->order(["JSON_EXTRACT(`props`, '$.\"Дата\".\"value\"') DESC"]);

        return $qb;
    }
}
