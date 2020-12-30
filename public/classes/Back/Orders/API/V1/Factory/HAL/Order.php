<?php

namespace Back\Orders\API\V1\Factory\HAL;
use Back\Orders\API\V1\Factory\QueryParams;

use Websm\Framework\Router\Router;

class Order {

    public static function getLinks($params) {

        $item = $params['item'];
        $origin = 'https://' . $_SERVER['SERVER_NAME'];

        $routes = [
            'self' => Router::byName('api:orders:v1:order')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'props' => Router::byName('api:orders:v1:order:props')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'printref' => Router::byName('api:orders:v1:order:printref')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'mailsender' => Router::byName('api:orders:v1:order:mailsender')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'smssender' => Router::byName('api:orders:v1:order:smssender')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'props' => [
                'href' => $origin . $routes['props'],
            ],
            'printref' => [
                'href' => $origin . $routes['printref'],
            ],
            'mailsender' => [
                'href' => $origin . $routes['mailsender'],
            ],
            'smssender' => [
                'href' => $origin . $routes['smssender'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        $item = $params['item'];
        $embed = QueryParams::getEmbed();

        $result = (Object)null;

        if (in_array('props', $embed))
            $result->props = Props::get([ 'item' => $item ]);

        return $result;
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => (Integer)$item->id,
            'user_id' => (Integer)$item->user_id,
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
