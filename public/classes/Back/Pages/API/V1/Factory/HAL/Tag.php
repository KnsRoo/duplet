<?php

namespace Back\Pages\API\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Tag
{

    public static function getLinks($params)
    {
        $item = $params['item'];

        return [
            'self' => [
                'href' => Router::byName('page:api:v1:tag')
                    ->getURL(['id' => $item->id]),
            ],
        ];
    }

    public static function getEmbedded($params)
    {
        return (object) null;
    }

    public static function get($params)
    {
        $item = $params['item'];

        return [
            'id' => $item->id,
            'name' => $item->title,
            'static' => (bool) $item->static,
            '_links' => self::getLinks([
                'item' => $item,
            ]),
            '_embedded' => self::getEmbedded([
                'item' => $item,
            ]),
        ];
    }
}
