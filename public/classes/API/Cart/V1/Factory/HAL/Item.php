<?php

namespace API\Cart\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Item {

    public static function getLinks($params) {

        $item = $params['item'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:cart:v1:item')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'items' => Router::byName('api:cart:v1:items')
                ->getAbsolutePath(),
        ];

        $selfUrl = $origin . $routes['self'];
        $itemsUrl = $origin . $routes['items'];

        return [
            'self' => [
                'href' => $selfUrl,
            ],
            'items' => [
                'href' => $itemsUrl,
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];
        $result = $item->asArray();
        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);
        return $result;
    }
}
