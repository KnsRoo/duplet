<?php

namespace Back\Catalog\API\V1\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class Props {

    private static $indexProps = 0;

    public static function filter($qb, $expr) {

        $res = self::propsExprToSql($expr);
        $sql = $res->sql;
        $params = $res->params;

        $qb = $qb->andWhere($sql, $params);
        return $qb;
    }

    private static function propsExprToSql($expr) {

        $sql = '( 1 )';
        $params = [];

        //?props={"has":{"Наличие в аптеках":"зиповская"}}
        //?props={"eq":{"Скидка": "1%"}}
        $op = null;
        if (property_exists($expr, 'eq')) {
            $op = 'eq';
        } else if (property_exists($expr, 'neq')) {
            $op = 'ne';
        } else if (property_exists($expr, 'ge')) {
            $op = 'ge';
        } else if (property_exists($expr, 'gt')) {
            $op = 'gt';
        } else if (property_exists($expr, 'le')) {
            $op = 'le';
        } else if (property_exists($expr, 'lt')) {
            $op = 'lt';
        } else if (property_exists($expr, 'has')) {
            $op = 'has';
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

        if (!$op)
            throw new BaseException("props: invalid operation ${op}");

        switch($op) {
            case 'eq':

                //?props={"eq":{"местность":"болото"}}
                $operands = $expr->eq;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) = CAST(:propval_${index} AS JSON)";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'ne':

                //?props={"ne":{"местность":"болото"}}
                $operands = $expr->ne;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) <> :propval_${index}";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'lt':

                //?props={"lt":{"высота":250}}
                $operands = $expr->lt;

                if (!is_object($operands) || !isset($operands{0}))
                    throw new BaseException("props: invalid operand for ${op}");

                $keys = get_object_keys($val);
                $key = $keys[0];
                $val = $operands{0};
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) < :propval_${index}";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'le':

                //?props={"te":{"высота":250}}
                $operands = $expr->le;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) <= :propval_${index}";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'gt':

                //?props={"gt":{"высота":250}}
                $operands = $expr->gt;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) > :propval_${index}";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'ge':

                //?props={"ge":{"высота":250}}
                $operands = $expr->ge;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_EXTRACT(`props`, :json_path_${index}) >= :propval_${index}";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'has':

                //?props={"has":{"местность":"болото"}}
                $operands = $expr->has;

                if (!is_object($operands))
                    throw new BaseException("props: invalid operand for ${op}");
                $key = key((Array)$operands);

                if (!$key)
                    throw new BaseException("props: invalid operand for ${op}");

                $val = $operands->$key;
                $path = "$.\"" . $key . "\".value";
                $index = static::$indexProps;
                $sql = "JSON_CONTAINS(`props`, CAST(:propval_${index} as JSON), :json_path_${index})";
                $params["propval_${index}"] = json_encode($val);
                $params["json_path_${index}"] = $path;
                static::$indexProps++;

                break;
            case 'and':

                //?props={"and":[{"lt":{"высота":250}},{"gt":{"высота":150}}]}
                $props = $expr->and;
                $exprArr = [];

                foreach ($props as $prop) {
                    $res = self::propsExprToSql($prop);
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

                //?props={"or":[{"lt":{"высота":250}},{"gt":{"высота":150}}]}
                $props = $expr->or;
                $exprArr = [];

                foreach ($props as $prop) {
                    $res = self::propsExprToSql($prop);
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
}
