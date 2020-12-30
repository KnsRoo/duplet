<?php

namespace Back\Catalog\API\V1\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class Tags {

    private static $indexTags = 0;
    private static $indexProps = 0;

    private static function tagsExprToSql($expr) {

        $sql = '( 1 )';
        $params = [];

        $op = null;
        if (property_exists($expr, 'eq')) {
            $op = 'eq';
        } else if (property_exists($expr, 'ne')) {
            $op = 'ne';
        } else if (property_exists($expr, 'and')) {
            $op = 'and';
        } else if (property_exists($expr, 'or')) {
            $op = 'or';
        } else {
            return (Object)[
                'sql' => $sql,
                'params' => $params,
            ];
        }

        if (!$op) throw new BaseException("tags: invalid operation ${op}");

        switch($op) {
            case 'eq':

                $val = $expr->eq;
                $index = static::$indexTags;
                $sql = "( tags LIKE :tagexpr_${index} )";
                $params["tagexpr_${index}"] = "%:${val}:%";
                static::$indexTags++;

                break;
            case 'ne':

                $val = $expr->ne;
                $index = static::$indexTags;
                $sql = "( tags NOT LIKE :tagexpr_${index} )";
                $params["tagexpr_${index}"] = "%:${val}:%";
                static::$indexTags++;

                break;
            case 'and':

                $tags = $expr->and;
                $exprArr = [];

                foreach ($tags as $tag) {
                    $res = self::tagsExprToSql($tag);
                    $exprArr[] = $res->sql;
                    $params = array_merge($params, $res->params);
                }

                if (count($exprArr) > 0) {
                    $sql = "(" . implode($exprArr, " AND ") . ")";
                } else {
                    $sql = '( 1 )';
                }

                break;
            case 'or':

                $tags = $expr->or;
                $exprArr = [];

                foreach ($tags as $tag) {
                    $res = self::tagsExprToSql($tag);
                    $exprArr[] = $res->sql;
                    $params = array_merge($params, $res->params);
                }

                if (count($exprArr) > 0) {
                    $sql = "(" . implode($exprArr, " OR ") . ")";
                } else {
                    $sql = '( 1 )';
                }

                break;
            default:
                break;
        }

        return (Object)[
            'sql' => $sql,
            'params' => $params,
        ];
    }

    public static function filter($qb, $expr) {

        $res = self::tagsExprToSql($expr);
        $sql = $res->sql;
        $params = $res->params;

        $qb = $qb->andWhere($sql, $params);
        return $qb;
    }
}
