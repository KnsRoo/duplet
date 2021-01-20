<?php

namespace API\Catalog\V3\Factory\Filters\QB;
use Websm\Framework\Exceptions\BaseException;

class Query {

    public static function filter($qb, $expr) {

        if ($expr === null)
            return $qb;

        $res = self::queryToSql($expr);

        $sql = $res->sql;
        $params = $res->params;

        $qb = $qb->andWhere($sql, $params);
        return $qb;
    }

    private static function queryToSql($expr) {

        //
        // Костыль для более правильного поиска
        //
        mb_regex_encoding('utf-8');
        $expr = mb_ereg_replace("/[^a-zA-ZА-Яа-я0-9\s]/u", " ", $expr);
        $exprArray = explode(' ', $expr);
        foreach ($exprArray as $key => $value) {
            if ($value === '') {
                unset($exprArray[$key]);
                continue;
            }
            $del = floor(mb_strlen($value, 'utf-8') * 0.15);
            if ($del > 0)
                $exprArray[$key] = mb_substr($value, 0, -$del, 'utf-8');
        }
        $expr = implode('%', $exprArray);
        //
        //
        //

        $or = [
            '(title LIKE :query)',
            /* '(preview LIKE :query)', */
            /* '(about LIKE :query)', */
            '(code LIKE :query)',
            '(tags LIKE :query)',
        ];

        $sql = implode(' OR ', $or);
        $params = [
            'query' => '%' . $expr . '%',
        ];

        return (Object)[
            'sql' => $sql,
            'params' => $params,
        ];
    }
}
