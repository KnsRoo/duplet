<?php

namespace Back\Pages\API\V1\Factory\Filters;

class QB
{

    public static function filterTags($qb, $tags)
    {
        foreach ($tags as $tag)
            $qb = $qb->andWhere('tags LIKE \'%:' . $tag . ':%\'');

        return $qb;
    }

    public static function filterProps($qb, $props)
    {
        foreach ($props as $prop) {

            $key = '';
            $op = '';
            $val = '';

            if (is_array($prop)) {

                if (count($prop) >= 3) {

                    $key = $prop[0];
                    $op = $prop[1];
                    $val = $prop[2];
                } else if (isset($prop[1])) {

                    $key = $prop[0];
                    $op = '=';
                    $val = $prop[1];
                }
            } else continue;

            $path = "$.\"" . $key . "\".value";
            switch ($op) {

                case '=':
                    $qb->andWhere("JSON_EXTRACT(`props`, '${path}') = :val", [
                        'val' => $val,
                    ]);
                    break;
                case '>=':
                    $qb->andWhere("JSON_EXTRACT(`props`, '${path}') >= :val", [
                        'val' => $val,
                    ]);
                    break;
                case '<=':
                    $qb->andWhere("JSON_EXTRACT(`props`, '${path}') <= :val", [
                        'val' => $val,
                    ]);
                    break;
                case '>':
                    $qb->andWhere("JSON_EXTRACT(`props`, '${path}') > :val", [
                        'val' => $val,
                    ]);
                    break;
                case '<':
                    $qb->andWhere("JSON_EXTRACT(`props`, '${path}') < :val", [
                        'val' => $val,
                    ]);
                    break;
                case 'contains':
                    $qb->andWhere("JSON_CONTAINS(`props`, :val, '${path}')", [
                        'val' => $val,
                    ]);
                    break;
                default:
                    break;
            }
        }

        return $qb;
    }

    public static function filterQuery($qb, $query)
    {
        if (is_string($query)) {

            $obj = json_decode($query);

            if ($obj !== null) {

                if (property_exists($obj, 'like'))
                    $qb = $qb->andWhere('`title` LIKE :querylike', ['querylike' => '%' . $obj->like . '%']);

                else if (property_exists($obj, 'nlike'))
                    $qb = $qb->andWhere('`title` NOT LIKE :querynlike', ['querynlike' => '%' . $obj->nlike . '%']);
            } else {

                $qb = $qb->andWhere('`title` LIKE :query', ['query' => '%' . $query . '%']);
            }
        }

        return $qb;
    }
}
