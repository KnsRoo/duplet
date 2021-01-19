<?php

namespace API\Catalog\V2\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class OrderProducts {

    const ORDERS = [
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    const ORDER_FIELDS = [ 
        'title' => 'title',
        'creationDate' => 'date',
        'code' => 'code',
        'price' => 'price',
        'sort' => 'sort',
    ];

    public static function easyFilter($qb, $order, $sort){
        if ($order && $sort)
            $qb = $qb->order(['`'.$order.'` '.$sort]);
        return $qb;
    }

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

                } else {

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
            }

            $qb = $qb->order($res);

        } else $qb = $qb->order(['`sort` ASC']);

        return $qb;
    }
}
