<?php

namespace Back\Catalog\API\V1\Factory\Filters;

class QB {

    public static function filterTags($qb, $tags) {

        foreach($tags as $tag)
            $qb = $qb->andWhere('tags LIKE \'%:' . $tag . ':%\'');

        return $qb;

    }

    public static function filterProps($qb, $props) {

        foreach ($props as $prop) {

            $key = '';
            $op = '';
            $val = '';

            if (is_array($prop)) {

                if (count($prop) >= 3) {

                    $key = $prop[0];
                    $op = $prop[1];
                    $val = $prop[2];

                } else if(isset($prop[1])){

                    $key = $prop[0];
                    $op = '=';
                    $val = $prop[1];

                }

            } else continue;

            $path = "$.\"" . $key . "\".value";
            switch($op) {

            case '=':
                $qb->andWhere("JSON_EXTRACT(`extra_properties`, '${path}') = :val", [
                    'val' => $val,
                ]);
                break;
            case '>=':
                $qb->andWhere("JSON_EXTRACT(`extra_properties`, '${path}') >= :val", [
                    'val' => $val,
                ]);
                break;
            case '<=':
                $qb->andWhere("JSON_EXTRACT(`extra_properties`, '${path}') <= :val", [
                    'val' => $val,
                ]);
                break;
            case '>':
                $qb->andWhere("JSON_EXTRACT(`extra_properties`, '${path}') > :val", [
                    'val' => $val,
                ]);
                break;
            case '<':
                $qb->andWhere("JSON_EXTRACT(`extra_properties`, '${path}') < :val", [
                    'val' => $val,
                ]);
                break;
            case 'contains':
                $qb->andWhere("JSON_CONTAINS(`extra_properties`, :val, '${path}')", [
                    'val' => $val,
                ]);
                break;
            default:
                continue;
            }

        }

        return $qb;
    }

    public static function filterQuery($qb, $query) {

        return $qb->andWhere('`title` LIKE :query', ['query' => '%'.$query.'%'])
            ->orWhere('`preview` LIKE :query')
            ->orWhere('`about` LIKE :query');
    }
}
