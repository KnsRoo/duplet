<?php

namespace API\User\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Order {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:user:v1:order')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        $props = (Object)json_decode($item->props);

        return [
            'id' => (String)$item->id,
            'props' => (Object)json_decode($item->props),
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
