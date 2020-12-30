<?php

namespace API\Pages\V1\Factory\Filters;

class QB {

    public static function filterTags($qb, $tags) {

        foreach($tags as $tag)
            $qb = $qb->orWhere('tags LIKE \'%:' . $tag . ':%\'');

        return $qb;

    }

    public static function filterQuery($qb, $query) {

        $qb = $qb->andWhere('title LIKE :query', [ 'query' => '%' . $query . '%' ])
            ->orWhere('text LIKE :query')
            ->orWhere('announce LIKE :query');

        return $qb;
    }
}
