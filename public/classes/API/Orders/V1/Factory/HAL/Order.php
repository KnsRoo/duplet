<?php

namespace API\Orders\V1\Factory\HAL;
use API\Orders\V1\Factory\QueryParams;

use Websm\Framework\Router\Router;
use Websm\Framework\Router\Request\Query;

use Websm\Framework\JWT\Client as JWTClient;

class Order {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:orders:v1:order')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        $embed = QueryParams::getEmbed();

        $embedded = (Object)null;
        if (in_array('token', $embed)) {
            $embed->token = '';
        }

        return $embedded;
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => (Integer)$item->id,
            'userId' => $item->user_id,
            'props' => json_decode($item->props),
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
