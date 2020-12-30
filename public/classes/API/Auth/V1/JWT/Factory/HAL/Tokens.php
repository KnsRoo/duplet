<?php

namespace API\Auth\V1\JWT\Factory\HAL;
use Websm\Framework\Router\Router;

class Tokens {

    public static function getLinks($params) {

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:auth:v1:jwt')
                ->getAbsolutePath(),
            'refresher' => Router::byName('api:auth:v1:jwt:refresher')
                ->getAbsolutePath(),
            'decoder' => Router::byName('api:auth:v1:jwt:decoder')
                ->getAbsolutePath(),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'refresher' => [
                'href' => $origin . $routes['refresher'],
            ],
            'decoder' => [
                'href' => $origin . $routes['decoder'],
            ],
        ];
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'accessToken' => $item->accessToken,
            'refreshToken' => $item->refreshToken,
            '_links' => self::getLinks(['item' => $item]),
        ];
    }
}
