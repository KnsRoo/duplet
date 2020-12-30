<?php

namespace API\News\V4\Factory\Filters;

class QB {

    public static function filterTags($qb, $tags) {

        foreach($tags as $tag)
            $qb = $qb->andWhere('tags LIKE \'%:' . $tag . ':%\'');

        return $qb;

    }
}
